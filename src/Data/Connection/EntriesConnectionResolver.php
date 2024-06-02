<?php
/**
 * ConnectionResolver - Entries
 *
 * Resolves connections to Entries.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\Connection;

use GFAPI;
use GF_Query;
use GraphQL\Error\UserError;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\Enum\FieldFiltersModeEnum;
use WPGraphQL\GF\Type\Enum\FieldFiltersOperatorInputEnum;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - EntriesConnectionResolver
 *
 * @extends \WPGraphQL\Data\Connection\AbstractConnectionResolver<\GF_Query>
 */
class EntriesConnectionResolver extends AbstractConnectionResolver {
	/**
	 * Offset index.
	 *
	 * @var int
	 */
	public ?int $offset_index;

	/**
	 * @var string[]
	 */
	private const OPERATORS_TO_LIMIT = [
		FieldFiltersOperatorInputEnum::CONTAINS,
		FieldFiltersOperatorInputEnum::IS,
		FieldFiltersOperatorInputEnum::IS_NOT,
		FieldFiltersOperatorInputEnum::LIKE,
	];

	/**
	 * {@inheritDoc}
	 */
	protected function loader_name(): string {
		return EntriesLoader::$name;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_query_args( array $args ): array {
		$query_args = [
			'form_ids'        => $this->prepare_form_ids( $args ),
			'search_criteria' => $this->prepare_search_criteria( $args ),
			'sorting'         => $this->prepare_sort( $args ),
			'paging'          => $this->prepare_paging( $args ),
		];

		/**
		 * Filter the $query args to allow folks to customize queries programmatically
		 *
		 * @param array<string,mixed> $query_args The args that will be passed to the GF_Query
		 * @param self                $resolver   The current instance of the resolver
		 */
		$query_args = apply_filters( 'graphql_gf_entries_connection_query_args', $query_args, $this );

		return $query_args;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function query_class(): string {
		return GF_Query::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function is_valid_query_class( string $query_class ): bool {
		return method_exists( $query_class, 'get_ids' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function query( array $query_args ): GF_Query {
		$form_ids        = $query_args['form_ids'] ?? null;
		$search_criteria = $query_args['search_criteria'] ?? null;
		$sorting         = $query_args['sorting'] ?? null;
		$paging          = $query_args['paging'] ?? null;

		return new GF_Query( $form_ids, $search_criteria, $sorting, $paging );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public function should_execute(): bool {
		$can_view = false;

		if (
			current_user_can( 'gravityforms_view_entries' ) ||
			current_user_can( 'gform_full_access' ) ) {
			$can_view = true;
		}

		$query_args = $this->get_query_args();

		/**
		 * Filter to control whether the user should be allowed to view entries.
		 *
		 * @since 0.10.0
		 *
		 * @param bool $can_view_entries Whether the current user should be allowed to view form entries.
		 * @param int|int[] $form_ids List of he specific form ID being queried.
		 */
		$can_view = apply_filters( 'graphql_gf_can_view_entries', $can_view, $query_args['form_ids'] );

		return $can_view;
	}

	/**
	 * Determine whether or not the the offset is valid, i.e the item corresponding to the offset exists.
	 * Offset is equivalent to WordPress ID (e.g post_id, term_id). So this function is equivalent
	 * to checking if the WordPress object exists for the given ID.
	 *
	 * @param int $offset The offset.
	 *
	 * @return bool Whether the offset is valid.
	 */
	public function is_valid_offset( $offset ) {
		return GFAPI::entry_exists( $offset );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_ids_from_query(): array {
		$query = $this->get_query();
		$ids   = $query->get_ids() ?: [];

		// If we're going backwards, we need to reverse the array.
		$args = $this->get_args();
		if ( ! empty( $args['last'] ) ) {
			$ids = array_reverse( $ids );
		}

		return $ids;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_offset_for_cursor( ?string $cursor = null ) {
		$offset = false;

		// Bail early if no cursor to offset.
		if ( empty( $cursor ) ) {
			return $offset;
		}

		$current_loc = $this->parse_cursor( $cursor );

		if ( isset( $current_loc['offset'] ) ) {
			$offset = $current_loc['offset'];
		}

		return is_numeric( $offset ) ? absint( $offset ) : $offset;
	}

	/**
	 * Validates Model.
	 *
	 * @param mixed $model model.
	 *
	 * @return bool
	 */
	protected function is_valid_model( $model ) {
		return ! empty( $model->databaseId ) || ! empty( $model->resumeToken );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_cursor_for_node( $id ): string {
		$args  = $this->get_args();
		$nodes = $this->get_nodes();

		// The GF_Query offset index is based on the original sort order.
		$nodes = ! empty( $args['last'] ) ? array_reverse( $nodes, true ) : $nodes;

		$index = $this->get_query_offset_index() + array_search( $id, array_keys( $nodes ), true );

		return base64_encode( 'arrayconnection:' . $index . ':' . $id );
	}

	/**
	 * Gets the array index for offsetting GF_Query.
	 */
	public function get_query_offset_index(): int {
		if ( ! isset( $this->offset_index ) ) {
			$args         = $this->get_args();
			$offset_index = 0;

			// If a first/last query, we need to find the offset index.
			if ( ! empty( $args['first'] ) ) {
				$cursor_to_offset = ! empty( $args['after'] ) ? $args['after'] : null;
			} elseif ( ! empty( $args['last'] ) ) {
				$cursor_to_offset = ! empty( $args['before'] ) ? $args['before'] : null;
			}

			if ( ! empty( $cursor_to_offset ) ) {
				$current_loc = $this->parse_cursor( $cursor_to_offset );

				if ( isset( $current_loc['index'] ) ) {
					$offset_index = $current_loc['index'] + 1;
				}
			}

			$this->offset_index = $offset_index;
		}

		return $this->offset_index;
	}

	/**
	 * Gets index (array offset) and offset (entry id) from decoded cursor.
	 *
	 * @param string $cursor .
	 *
	 * @return array{index:int,offset:int}
	 */
	protected function parse_cursor( string $cursor ): array {
		$decoded     = base64_decode( $cursor );
		$current_loc = explode( ':', $decoded );

		return [
			'index'  => absint( $current_loc[1] ),
			'offset' => absint( $current_loc[2] ),
		];
	}

	/**
	 * Prepares the form IDs for the query.
	 *
	 * @param array<string,mixed> $args The GraphQL args.
	 *
	 * @return int[]|int
	 */
	private function prepare_form_ids( array $args ) {
		if ( empty( $args['where']['formIds'] ) ) {
			return 0;
		}

		// Convert single form ID to array.
		if ( ! is_array( $args['where']['formIds'] ) ) {
			$args['where']['formIds'] = [ $args['where']['formIds'] ];
		}

		return array_map(
			static fn ( $id ) => Utils::get_form_id_from_id( $id ),
			$args['where']['formIds']
		);
	}

	/**
	 * Prepares the search criteria for the query.
	 *
	 * @param array<string,mixed> $args The GraphQL args.
	 *
	 * @return array<string,mixed>
	 */
	private function prepare_search_criteria( array $args ): array {
		$search_criteria = $this->apply_status_to_search_criteria( [] );

		if ( ! empty( $args['where']['dateFilters']['startDate'] ) ) {
			$search_criteria['start_date'] = sanitize_text_field( $args['where']['dateFilters']['startDate'] );
		}

		if ( ! empty( $args['where']['dateFilters']['endDate'] ) ) {
			$search_criteria['end_date'] = sanitize_text_field( $args['where']['dateFilters']['endDate'] );
		}

		if ( ! empty( $args['where']['fieldFilters'] ) && is_array( $args['where']['fieldFilters'] ) ) {
			$search_criteria['field_filters'] = array_merge(
				[ 'mode' => $args['where']['fieldFiltersMode'] ?? FieldFiltersModeEnum::ALL ],
				$this->format_field_filters( $args['where']['fieldFilters'] )
			);
		}

		return $search_criteria;
	}

	/**
	 * Adds 'status' value to search criteria.
	 *
	 * @param array<string,mixed> $search_criteria The search criteria for the entry Ids.
	 *
	 * @return array<string,mixed>
	 */
	private function apply_status_to_search_criteria( array $search_criteria ): array {
		$args = $this->get_args();

		$status = $args['where']['status'] ?? EntryStatusEnum::ACTIVE; // Default to active entries.

		// For all entries, don't add a 'status' value to search criteria.
		if ( 'ALL' === $status ) {
			return $search_criteria;
		}

		$search_criteria['status'] = sanitize_text_field( $status );

		return $search_criteria;
	}

	/**
	 * Correctly formats the field filters for search criteria.
	 *
	 * @param array<string,mixed>[] $field_filters The field filters.
	 *
	 * @return array{key?:string,operator:string,value:mixed}[]
	 */
	private function format_field_filters( array $field_filters ): array {
		return array_reduce(
			$field_filters,
			function ( $field_filters, $field_filter ) {
				$key      = empty( $field_filter['key'] ) ? 0 : sanitize_text_field( $field_filter['key'] );
				$operator = $field_filter['operator'] ?? FieldFiltersOperatorInputEnum::IN; // Default to "in".
				$value    = $this->get_field_filter_value( $field_filter, $operator );

				// Key should be omitted when searching all of them.
				$field_filters[] = 0 === $key ? compact( 'operator', 'value' ) : compact( 'key', 'operator', 'value' );

				return $field_filters;
			},
			[]
		);
	}

	/**
	 * Gets filter value for each field used in search criteria.
	 *
	 * @param array<string,mixed> $field_filter Field filter.
	 * @param string              $operator     Operator.
	 *
	 * @throws \GraphQL\Error\UserError Field filters must have one value field.
	 *
	 * @return mixed|mixed[]
	 */
	private function get_field_filter_value( array $field_filter, string $operator ) {
		$value_fields = $this->get_field_filter_value_fields( $field_filter );

		if ( 1 !== count( $value_fields ) ) {
			throw new UserError( esc_html__( 'Every field filter must have one value field.', 'wp-graphql-gravity-forms' ) );
		}

		$field_filter_values = array_map( 'sanitize_text_field', $field_filter[ $value_fields[0] ] );

		if ( $this->should_field_filter_be_limited_to_single_value( $operator ) ) {
			if ( 1 !== count( $field_filter_values ) ) {
				throw new UserError(
					// translators: FieldFiltersOperatorInputEnum.
					sprintf( esc_html__( '%s requires passing only a single value. Array passed.', 'wp-graphql-gravity-forms' ), esc_html( $operator ) )
				);
			}

			return $field_filter_values[0] ?? '';
		}

		return $field_filter_values;
	}

	/**
	 * Returns whether field filter should be limited to a single value.
	 *
	 * @param string $operator Operator.
	 */
	private function should_field_filter_be_limited_to_single_value( string $operator ): bool {
		return in_array( $operator, self::OPERATORS_TO_LIMIT, true );
	}

	/**
	 * Get value fields for the field filter.
	 *
	 * @param array<string,mixed> $field_filter Field filter.
	 *
	 * @return string[]
	 */
	private function get_field_filter_value_fields( array $field_filter ): array {
		return array_values(
			array_filter(
				[ 'stringValues', 'intValues', 'floatValues', 'boolValues' ],
				static function ( $value_field ) use ( $field_filter ): bool {
					return ! empty( $field_filter[ $value_field ] );
				}
			)
		);
	}

	/**
	 * Prepares the sort argument for the query.
	 *
	 * @param array<string,mixed> $args The GraphQL args.
	 *
	 * @return array<string,mixed>
	 */
	private function prepare_sort( array $args ): array {
		// Set default sort direction.
		$sort = [
			'direction' => 'DESC',
		];

		if ( ! empty( $args['where']['orderby'] ) && is_array( $args['where']['orderby'] ) ) {
			$sort = [
				'key'        => $args['where']['orderby']['key'] ?? '',
				'direction'  => $args['where']['orderby']['order'] ?? 'ASC',
				'is_numeric' => $args['where']['orderby']['isNumeric'] ?? false,
			];
		}

		// Flip the direction on `last` query.
		if ( ! empty( $args['last'] ) ) {
			$sort['direction'] = 'ASC' === $sort['direction'] ? 'DESC' : 'ASC';
		}

		return $sort;
	}

	/**
	 * Prepare paging arguments for the query.
	 *
	 * @param array<string,mixed> $args The GraphQL args.
	 *
	 * @return array{offset:int,page_size:int}
	 *
	 * @throws \GraphQL\Error\UserError When using unsupported pagination.
	 */
	private function prepare_paging( array $args ): array {
		return [
			'offset'    => $this->get_query_offset_index(),
			'page_size' => $this->get_query_amount() + 1, // overfetch for prev/next.
		];
	}
}
