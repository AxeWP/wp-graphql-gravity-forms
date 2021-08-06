<?php
/**
 * ConnectionResolver - RootQueryEntry
 *
 * Resolves connections to Entries.
 *
 * @package WPGraphQLGravityForms\Connections
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GFForms;
use GFFormsModel;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Error\UserError;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQLGravityForms\Data\Loader\FormsLoader;
use WPGraphQLGravityForms\Types\Enum\FormStatusEnum;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - FormsConnectionResolver
 */
class FormsConnectionResolver extends AbstractConnectionResolver {
	/**
	 * {@inheritDoc}
	 */
	public function should_execute() : bool {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_loader_name() : string {
		return FormsLoader::$name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_valid_offset( $offset ) {
		return (bool) GFAPI::get_form( $offset );
	}

	/**
	 * Validates Model.
	 *
	 * If model isn't a class with a `fields` member, this function with have be overridden in
	 * the Connection class.
	 *
	 * @param array $model model.
	 *
	 * @return bool
	 */
	protected function is_valid_model( $model ) {
		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError When using `formIds` and `status` together.
	 */
	public function get_query_args() : array {
		/**
		 * Throw error if trying to filter `where.formIds` by `where.status`.
		 */
		if ( isset( $this->args['where']['formIds'] ) && isset( $this->args['where']['status'] ) ) {
				throw new UserError( __( 'Sorry, filtering by `formIds` and `status` simultaneously is not currently supported.', 'wp-graphql-gravity-forms' ) );
		}

		$query_args = [
			'form_ids' => $this->get_form_ids(),
			'status'   => $this->get_form_status(),
			'sort'     => $this->get_sort(),
		];

		/**
		 * Filter the $query args to allow folks to customize queries programmatically
		 *
		 * @param array       $query_args The args that will be passed to the WP_Query
		 * @param mixed       $source     The source that's passed down the GraphQL queries
		 * @param array       $args       The inputArgs on the field
		 * @param AppContext  $context    The AppContext passed down the GraphQL tree
		 * @param ResolveInfo $info       The ResolveInfo passed down the GraphQL tree
		 */
		$query_args = apply_filters( 'wp_graphql_gf_forms_connection_query_args', $query_args, $this->source, $this->args, $this->context, $this->info );

		return $query_args;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array
	 */
	public function get_query() : array {
		$form_ids    = $this->get_ids();
		$active      = $this->query_args['status']['active'];
		$sort_column = $this->query_args['sort']['key'];
		$sort_dir    = $this->query_args['sort']['direction'];

		// Used to return trashed and untrashed entries, if formIds are passed, and no where args are limiting it.
		$trash = ! empty( $form_ids ) && ! isset( $this->args['where']['status'] ) ? true : $this->query_args['status']['trash'];

		$forms = GFUtils::get_forms( $form_ids, $active, $trash, $sort_column, $sort_dir );

		$query = [];

		foreach ( $forms as $form ) {
			/**
			 * "wp_graphql_gf_form_object" filter
			 *
			 * Provides the ability to manipulate the form data before it is sent to the
			 * client. This hook is somewhat similar to Gravity Forms' gform_pre_render hook
			 * and can be used for dynamic field input population, among other things.
			 *
			 * @param array $form Form meta array.
			 */
			$query[ $form['id'] ] = apply_filters( 'wp_graphql_gf_form_object', $form );
		}

		return $query;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_ids() : array {
		$form_ids    = $this->query_args['form_ids'];
		$active      = $this->query_args['status']['active'];
		$trash       = $this->query_args['status']['trash'];
		$sort_column = $this->query_args['sort']['key'];
		$sort_dir    = $this->query_args['sort']['direction'];

		$ids_to_return = ! empty( $form_ids ) ? $form_ids : GFFormsModel::get_form_ids( $active, $trash, $sort_column, $sort_dir );

		if ( isset( $this->args['after'] ) ) {
			$key           = array_search( (string) $this->get_offset(), $ids_to_return, true );
			$ids_to_return = array_slice( $ids_to_return, $key + 1, null, true );
		}

		if ( isset( $this->args['before'] ) ) {
			$ids_to_return = array_reverse( $ids_to_return );
			$key           = array_search( (string) $this->get_offset(), $ids_to_return, true );
			$ids_to_return = array_slice( $ids_to_return, $key + 1, null, true );
			$ids_to_return = array_reverse( $ids_to_return );
		}

		$ids_to_return = array_slice( $ids_to_return, 0, $this->query_amount, true );

		return $ids_to_return;
	}

	/**
	 * Returns form ids.
	 *
	 * @return array
	 */
	private function get_form_ids() : array {
		if ( ! empty( $this->args['where']['formIds'] ) && is_array( $this->args['where']['formIds'] ) ) {
			return array_map( 'absint', $this->args['where']['formIds'] );
		}

		return [];
	}


	/**
	 * Gets form status from query.
	 *
	 * @return array
	 */
	private function get_form_status() : array {
		$status = $this->args['where']['status'] ?? FormStatusEnum::ACTIVE;
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
	 * Get sort argument for forms ID query.
	 *
	 * @return array
	 *
	 * @throws UserError .
	 */
	private function get_sort() : array {
		$sort = [
			'key'       => '',
			'direction' => 'DESC',
		];

		if ( ! empty( $this->args['where']['sort'] ) && is_array( $this->args['where']['sort'] ) ) {
			if ( version_compare( '2.5.0', GFForms::$version, '>' ) ) {
				throw new UserError(
					sprintf(
						// translators: Gravity Forms version.
						__( 'The `RootQueryToGravityFormsFormConnection.sort` argument requires Gravity Forms v2.5.0+. Version installed: %1$s', 'wp-graphql-gravity-forms' ),
						GFForms::$version
					)
				);
			}

			$sort = [
				'key'       => $this->args['where']['sort']['key'] ?? '',
				'direction' => $this->args['where']['sort']['direction'] ?? 'ASC',
			];
		}

		// Flip the direction on `last` query.
		if ( ! empty( $this->args['last'] ) ) {
			$sort['direction'] = 'ASC' === $sort['direction'] ? 'DESC' : 'ASC';
		}

		return $sort;
	}
}
