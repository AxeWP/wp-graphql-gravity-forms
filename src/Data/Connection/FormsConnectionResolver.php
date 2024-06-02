<?php
/**
 * ConnectionResolver - Forms
 *
 * Resolves connections to Forms.
 *
 * @package WPGraphQL\GF\Data\Connection
 * @since 0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\Connection;

use GFAPI;
use GraphQL\Error\UserError;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Type\Enum\FormStatusEnum;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FormsConnectionResolver
 *
 * @extends \WPGraphQL\Data\Connection\AbstractConnectionResolver<array<int|string,array<string,mixed>>>
 */
class FormsConnectionResolver extends AbstractConnectionResolver {
	/**
	 * {@inheritDoc}
	 */
	protected function loader_name(): string {
		return FormsLoader::$name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_valid_offset( $offset ) {
		return GFAPI::form_id_exists( $offset );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function is_valid_model( $model ) {
		return ! empty( $model->databaseId );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \GraphQL\Error\UserError When using `formIds` and `status` together.
	 */
	protected function prepare_args( $args ): array {
		// Throw error if trying to filter `where.formIds` by `where.status`.
		if ( isset( $args['where']['formIds'] ) && isset( $args['where']['status'] ) ) {
			throw new UserError( esc_html__( 'Sorry, filtering by `formIds` and `status` simultaneously is not currently supported.', 'wp-graphql-gravity-forms' ) );
		}

		return $args;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_query_args( $args ): array {
		$query_args = [
			'form_ids' => $this->prepare_form_ids( $args ),
			'status'   => $this->prepare_form_status( $args ),
			'sort'     => $this->prepare_sort( $args ),
		];

		/**
		 * Filter the $query args to allow folks to customize queries programmatically
		 *
		 * @param array<string,mixed> $query_args The args that will be passed to GFAPI::get_forms().
		 * @param self                $resolver   The current instance of the resolver
		 */
		$query_args = apply_filters( 'graphql_gf_forms_connection_query_args', $query_args, $this );

		return $query_args;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function query( array $query_args ) {
		$form_ids    = $query_args['form_ids'] ?? [];
		$active      = ! empty( $query_args['status']['active'] );
		$sort_column = (string) $query_args['sort']['key'];
		$sort_dir    = (string) $query_args['sort']['direction'];

		// Used to return trashed and untrashed entries, if formIds are passed, and no where args are limiting it.
		$graphql_args = $this->get_args();
		$trash        = ! empty( $form_ids ) && ! isset( $graphql_args['where']['status'] ) ? true : $query_args['status']['trash'];

		$forms = GFUtils::get_forms( $form_ids, $active, $trash, $sort_column, $sort_dir );

		$query = [];

		$loader = $this->get_loader();

		foreach ( $forms as $form ) {
			/**
			 * "wp_graphql_gf_form_object" filter
			 *
			 * Provides the ability to manipulate the form data before it is sent to the
			 * client. This hook is somewhat similar to Gravity Forms' gform_pre_render hook
			 * and can be used for dynamic field input population, among other things.
			 *
			 * @param array<string,mixed> $form Form meta array.
			 * @param self                $resolver   The current instance of the resolver
			 */
			$modified_form = apply_filters( 'graphql_gf_form_object', $form, $this );

			// Cache the form in the dataloader.
			$loader->prime( $modified_form['id'], $modified_form );

			$query[ $modified_form['id'] ] = $modified_form;
		}

		return $query;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_ids_from_query() {
		$queried = $this->get_query();
		$ids     = ! empty( $queried ) ? array_keys( $queried ) : [];

		// If we're going backwards, we need to reverse the array.
		$args = $this->get_args();
		if ( ! empty( $args['last'] ) ) {
			$ids = array_reverse( $ids );
		}

		return $ids;
	}

	/**
	 * Prepares form ids for query.
	 *
	 * @param array<string,mixed> $args GraphQL args.
	 *
	 * @return int[]
	 */
	private function prepare_form_ids( array $args ): array {
		if ( empty( $args['where']['formIds'] ) ) {
			return [];
		}

		if ( ! is_array( $args['where']['formIds'] ) ) {
			$args['where']['formIds'] = [ $args['where']['formIds'] ];
		}

		return array_map( static fn ( $id ) => Utils::get_form_id_from_id( $id ), $args['where']['formIds'] );
	}

	/**
	 * Prepare form status for query.
	 *
	 * @param array<string,mixed> $args GraphQL args.
	 *
	 * @return array{active:bool,trash:bool}
	 */
	private function prepare_form_status( array $args ): array {
		$status = $args['where']['status'] ?? FormStatusEnum::ACTIVE;

		if ( FormStatusEnum::INACTIVE === $status ) {
			return [
				'active' => false,
				'trash'  => false,
			];
		}

		if ( FormStatusEnum::TRASHED === $status ) {
			return [
				'active' => true,
				'trash'  => true,
			];
		}

		if ( FormStatusEnum::INACTIVE_TRASHED === $status ) {
			return [
				'active' => false,
				'trash'  => true,
			];
		}

		// Get forms that are active and not in the trash by default.
		return [
			'active' => true,
			'trash'  => false,
		];
	}

	/**
	 * Prepare sort argument for query.
	 *
	 * @param array<string,mixed> $args GraphQL args.
	 *
	 * @return array{key:string,direction:string}
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	private function prepare_sort( array $args ): array {
		$sort = [
			'key'       => '',
			'direction' => 'DESC',
		];

		if ( ! empty( $args['where']['orderby'] ) && is_array( $args['where']['orderby'] ) ) {
			// @todo remove support for deprecated `field` input.
			if ( empty( $args['where']['orderby']['column'] ) && ! empty( $args['where']['orderby']['field'] ) ) {
				$args['where']['orderby']['column'] = $args['where']['orderby']['field'];
			}

			$sort = [
				'key'       => $args['where']['orderby']['column'] ?? '',
				'direction' => $args['where']['orderby']['order'] ?? 'ASC',
			];
		}

		// Flip the direction on `last` query.
		if ( ! empty( $args['last'] ) ) {
			$sort['direction'] = 'ASC' === $sort['direction'] ? 'DESC' : 'ASC';
		}

		return $sort;
	}
}
