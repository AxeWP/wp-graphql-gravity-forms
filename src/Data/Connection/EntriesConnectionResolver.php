<?php
/**
 * ConnectionResolver - Entries
 *
 * Resolves connections to Entries.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Data\Connection;

use GF_Query;
use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\Enum\FieldFiltersModeEnum;
use WPGraphQL\GF\Type\Enum\FieldFiltersOperatorInputEnum;

/**
 * Class - EntriesConnectionResolver
 */
class EntriesConnectionResolver extends AbstractConnectionResolver {
	/**
	 * Offset index.
	 *
	 * @var integer
	 */
	public int $offset_index = 0;

	/**
	 * Whether the version of WPGraphQL is missing the new methods added in v1.9.0.
	 * Used to support pre 1.9.0 versions of WPGraphQL.
	 *
	 * @var boolean
	 */
	public bool $is_legacy = false;

	/**
	 * {@inheritDoc}
	 */
	public function __construct( $source, array $args, AppContext $context, ResolveInfo $info ) {
		$this->is_legacy = version_compare( WPGRAPHQL_VERSION, '1.9.0', '<' );

		parent::__construct( $source, $args, $context, $info );
		$this->offset_index = $this->get_query_offset_index();
	}

	/**
	 * Return the name of the loader to be used with the connection resolver
	 *
	 * @return string
	 */
	public function get_loader_name() : string {
		return EntriesLoader::$name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_query_args() : array {
		$query_args = [
			'form_ids'        => $this->get_form_ids(),
			'search_criteria' => $this->get_search_criteria(),
			'sorting'         => $this->get_sort(),
			'paging'          => $this->get_paging(),
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
		$query_args = apply_filters( 'graphql_gf_entries_connection_query_args', $query_args, $this->source, $this->args, $this->context, $this->info );

		return $query_args;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_query() : GF_Query {
		$form_ids        = $this->query_args['form_ids'];
		$search_criteria = $this->query_args['search_criteria'];
		$sorting         = $this->query_args['sorting'];
		$paging          = $this->query_args['paging'];

		return new GF_Query( $form_ids, $search_criteria, $sorting, $paging );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	public function should_execute() : bool {
		$can_view = false;

		if (
			current_user_can( 'gravityforms_view_entries' ) ||
			current_user_can( 'gform_full_access' ) ) {
			$can_view = true;
		}

		/**
		 * Filter to control whether the user should be allowed to view entries.
		 *
		 * @since 0.10.0
		 *
		 * @param bool $can_view_entries Whether the current user should be allowed to view form entries.
		 * @param int|int[] $form_ids List of he specific form ID being queried.
		 */
		$can_view = apply_filters( 'graphql_gf_can_view_entries', $can_view, $this->get_form_ids() );

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
	public function get_ids_from_query() : array {
		$ids = $this->query->get_ids() ?: [];

		// If we're going backwards, we need to reverse the array.
		if ( ! empty( $this->args['last'] ) ) {
			$ids = array_reverse( $ids );
		}

		return $ids;
	}

	/**
	 * Gets the array index for the given offset.
	 *
	 * Shim for pre 1.9.0 versions of WPGraphQL.
	 *
	 * @param int|string|false $offset The cursor pagination offset.
	 * @param array            $ids    The array of ids from the query.
	 *
	 * @return int|false $index The array index of the offset.
	 */
	public function get_array_index_for_offset( $offset, $ids ) {
		if ( ! $this->is_legacy ) {
			return parent::get_array_index_for_offset( $offset, $ids );
		}

		if ( false === $offset ) {
			return false;
		}

		// We use array_values() to ensure we're getting a positional index, and not a key.
		return array_search( $offset, array_values( $ids ), true );
	}

	/**
	 * Returns an array slice of IDs, per the Relay Cursor Connection spec.
	 *
	 * Shim for WPGraphQL < 1.9.0
	 *
	 * @param array $ids The array of IDs from the query to slice, ordered as expected by the GraphQL query.
	 *
	 * @return array
	 */
	public function apply_cursors_to_ids( array $ids ) {
		if ( ! $this->is_legacy ) {
			return parent::apply_cursors_to_ids( $ids );
		}

		if ( empty( $ids ) ) {
			return [];
		}

		// First we slice the array from the front.
		if ( ! empty( $this->args['after'] ) ) {
			$offset = $this->get_offset_for_cursor( $this->args['after'] );
			$index  = $this->get_array_index_for_offset( $offset, $ids );

			if ( false !== $index ) {
				// We want to start with the first id after the index.
				$ids = array_slice( $ids, $index + 1, null, true );
			}
		}

		// Then we slice the array from the back.
		if ( ! empty( $this->args['before'] ) ) {
			$offset = $this->get_offset_for_cursor( $this->args['before'] );
			$index  = $this->get_array_index_for_offset( $offset, $ids );

			if ( false !== $index ) {
				// Because array indexes start at 0, we can overfetch without adding 1 to $index.
				$ids = array_slice( $ids, 0, $index, true );
			}
		}

		return $ids;
	}

	/**
	 * Returns an array of IDs for the connection.
	 *
	 * Shim for WPGraphQL < 1.9.0
	 *
	 * @return array
	 */
	public function get_ids() {
		if ( ! $this->is_legacy ) {
			return parent::get_ids();
		}
		$ids = $this->get_ids_from_query();

		return $this->apply_cursors_to_ids( $ids );
	}


	/**
	 * {@inheritDoc}
	 */
	public function get_offset_for_cursor( string $cursor = null ) {
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
		return isset( $model->databaseId ) || empty( $model->resumeToken );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_cursor_for_node( $id ) : string {
		// The GF_Query offset index is based on the original sort order.
		$nodes = ! empty( $this->args['last'] ) ? array_reverse( $this->nodes, true ) : $this->nodes;

		$index = $this->offset_index + array_search( $id, array_keys( $nodes ), true );

		return base64_encode( 'arrayconnection:' . $index . ':' . $id );  // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}

	/**
	 * Gets the array index for offsetting GF_Query.
	 */
	public function get_query_offset_index() : int {
		$offset_index = 0;

		if ( ! empty( $this->args['first'] ) ) {
			$cursor_to_offset = ! empty( $this->args['after'] ) ? $this->args['after'] : null;
		} elseif ( ! empty( $this->args['last'] ) ) {
			$cursor_to_offset = ! empty( $this->args['before'] ) ? $this->args['before'] : null;
		}

		if ( ! empty( $cursor_to_offset ) ) {
			$current_loc = $this->parse_cursor( $cursor_to_offset );

			if ( isset( $current_loc['index'] ) ) {
				$offset_index = $current_loc['index'] + 1;
			}
		}

		return $offset_index;
	}

	/**
	 * Gets index (array offset) and offset (entry id) from decoded cursor.
	 *
	 * @param string $cursor .
	 * @return array
	 */
	protected function parse_cursor( string $cursor ) : array {
		$decoded     = base64_decode( $cursor ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		$current_loc = explode( ':', $decoded );

		return [
			'index'  => absint( $current_loc[1] ),
			'offset' => absint( $current_loc[2] ),
		];
	}

	/**
	 * Returns form ids.
	 *
	 * @return array|int
	 */
	private function get_form_ids() {
		if ( empty( $this->args['where']['formIds'] ) ) {
			return 0;
		}

		// @todo allow for both Global and DB Ids.
		if ( is_array( $this->args['where']['formIds'] ) ) {
			return array_map( 'absint', $this->args['where']['formIds'] );
		}

		return absint( $this->args['where']['formIds'] );
	}

	/**
	 * Gets search criteria for entry Ids.
	 *
	 * @return array
	 */
	private function get_search_criteria() : array {
		$search_criteria = $this->apply_status_to_search_criteria( [] );

		if ( ! empty( $this->args['where']['dateFilters']['startDate'] ) ) {
			$search_criteria['start_date'] = sanitize_text_field( $this->args['where']['dateFilters']['startDate'] );
		}

		if ( ! empty( $this->args['where']['dateFilters']['endDate'] ) ) {
			$search_criteria['end_date'] = sanitize_text_field( $this->args['where']['dateFilters']['endDate'] );
		}

		if ( ! empty( $this->args['where']['fieldFilters'] ) && is_array( $this->args['where']['fieldFilters'] ) ) {
			$search_criteria['field_filters'] = array_merge(
				[ 'mode' => $this->args['where']['fieldFiltersMode'] ?? FieldFiltersModeEnum::ALL ],
				$this->format_field_filters( $this->args['where']['fieldFilters'] )
			);
		}

		return $search_criteria;
	}

	/**
	 * Adds 'status' value to search criteria.
	 *
	 * @param array $search_criteria The search criteria for the entry Ids.
	 * @return array
	 */
	private function apply_status_to_search_criteria( array $search_criteria ) : array {
		$status = $this->args['where']['status'] ?? EntryStatusEnum::ACTIVE; // Default to active entries.

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
	 * @param array $field_filters .
	 * @return array
	 */
	private function format_field_filters( array $field_filters ) : array {
		return array_reduce(
			$field_filters,
			function( $field_filters, $field_filter ) {
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
	 * @param array  $field_filter Field filter.
	 * @param string $operator     Operator.
	 *
	 * @return mixed Filter value.
	 *
	 * @throws UserError Field filters must have one value field.
	 */
	private function get_field_filter_value( array $field_filter, string $operator ) {
		$value_fields = $this->get_field_filter_value_fields( $field_filter );

		if ( 1 !== count( $value_fields ) ) {
			throw new UserError( __( 'Every field filter must have one value field.', 'wp-graphql-gravity-forms' ) );
		}

		$field_filter_values = array_map( 'sanitize_text_field', $field_filter[ $value_fields[0] ] );

		if ( $this->should_field_filter_be_limited_to_single_value( $operator ) ) {
			if ( 1 !== count( $field_filter_values ) ) {
				throw new UserError(
					// translators: FieldFiltersOperatorInputEnum.
					sprintf( __( '%s requires passing only a single value. Array passed.', 'wp-graphql-gravity-forms' ), $operator )
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
	 * @return boolean
	 */
	private function should_field_filter_be_limited_to_single_value( string $operator ) : bool {
		$operators_to_limit = [
			FieldFiltersOperatorInputEnum::CONTAINS,
			FieldFiltersOperatorInputEnum::IS,
			FieldFiltersOperatorInputEnum::IS_NOT,
			FieldFiltersOperatorInputEnum::LIKE,
		];

		return in_array( $operator, $operators_to_limit, true );
	}

	/**
	 * Get value fields for the field filter.
	 *
	 * @param array $field_filter .
	 * @return array
	 */
	private function get_field_filter_value_fields( array $field_filter ) : array {
		return array_values(
			array_filter(
				[ 'stringValues', 'intValues', 'floatValues', 'boolValues' ],
				function( $value_field ) use ( $field_filter ) {
					return ! empty( $field_filter[ $value_field ] );
				}
			)
		);
	}

	/**
	 * Get sort argument for entry ID query.
	 *
	 * @return array
	 */
	private function get_sort() : array {
		// Set default sort direction.
		$sort = [
			'direction' => 'DESC',
		];

		if ( ! empty( $this->args['where']['orderby'] ) && is_array( $this->args['where']['orderby'] ) ) {
			$sort = [
				'key'        => $this->args['where']['orderby']['key'] ?? '',
				'direction'  => $this->args['where']['orderby']['order'] ?? 'ASC',
				'is_numeric' => $this->args['where']['orderby']['isNumeric'] ?? false,
			];
		}

		// Flip the direction on `last` query.
		if ( ! empty( $this->args['last'] ) ) {
			$sort['direction'] = 'ASC' === $sort['direction'] ? 'DESC' : 'ASC';
		}

		return $sort;
	}

	/**
	 * Get paging arguments for entry ID query.
	 *
	 * @return array
	 * @throws UserError When using unsupported pagination.
	 */
	private function get_paging() : array {
		return [
			'offset'    => $this->get_query_offset_index(),
			'page_size' => $this->get_query_amount() + 1, // overfetch for prev/next.
		];
	}
}
