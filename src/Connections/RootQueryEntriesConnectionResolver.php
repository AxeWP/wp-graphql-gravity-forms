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
use GraphQL\Error\UserError;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQLGravityForms\Data\Loader\EntriesLoader;
use WPGraphQLGravityForms\Types\Enum\EntryStatusEnum;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersModeEnum;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersOperatorInputEnum;

/**
 * Class - RootQueryEntriesConnectionResolver
 */
class RootQueryEntriesConnectionResolver extends AbstractConnectionResolver {
	/**
	 * Returns whether query should execute.
	 *
	 * @return bool
	 */
	public function should_execute() : bool {
		return current_user_can( 'gravityforms_view_entries' );
	}

	/**
	 * Return the name of the loader to be used with the connection resolver
	 *
	 * @return string
	 */
	public function get_loader_name() : string {
		return EntriesLoader::NAME;
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
		return true;
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
	 * Returns query arguments.
	 *
	 * @return array
	 */
	public function get_query_args() : array {
		return [];
	}

	/**
	 * Returns query to use for data fetching.
	 *
	 * @return array
	 */
	public function get_query() : array {
		return [];
	}

	/**
	 * Returns base-64 encoded cursor value.
	 *
	 * @param int $id Node ID.
	 *
	 * @return string
	 */
	protected function get_cursor_for_node( $id ) : string {
		$first        = $this->args['first'] ?? 20;
		$after_cursor = ! empty( $this->args['after'] ) ? json_decode( base64_decode( $this->args['after'] ), true ) : null; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		$index        = array_search( $id, array_keys( $this->nodes ), true );

		/**
		 * @ TODO:
		 * $last  = $this->args['last'] ?? 20;
		 * $before_cursor = ! empty( $this->args['before'] ) ? json_decode( base64_decode( $this->args['before'] ), true ) : null;
		 */

		$cursor = [
			'offset' => $after_cursor ? $after_cursor['offset'] + $after_cursor['index'] + 1 : 0,
			'index'  => $index,
		];

		$json_cursor = wp_json_encode( $cursor );

		return $json_cursor ? base64_encode( $json_cursor ) : ''; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}

	/**
	 * Return an array of ids from the query
	 *
	 * Each Query class in WP and potential datasource handles this differently, so each connection
	 * resolver should handle getting the items into a uniform array of items.
	 *
	 * @return array
	 *
	 * @throws UserError Pagination is not currently supported.
	 */
	public function get_ids() : array {
		if ( isset( $this->args['last'] ) || isset( $this->args['before'] ) ) {
			throw new UserError( __( 'Sorry, last/before pagination is currently not supported.', 'wp-graphql-gravity-forms' ) );
		}

		$entry_ids = GFAPI::get_entry_ids(
			$this->get_form_ids(),
			$this->get_search_criteria(),
			$this->get_sort(),
			$this->get_paging(),
		);

		return array_map( 'absint', $entry_ids );
	}

	/**
	 * Returns form ids.
	 *
	 * @return array|int
	 */
	private function get_form_ids() {
		if ( ! empty( $this->args['where']['formIds'] ) && is_array( $this->args['where']['formIds'] ) ) {
			return array_map( 'absint', $this->args['where']['formIds'] );
		}

		return 0; // Return all forms.
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
				if ( empty( $field_filter['key'] ) ) {
					throw new UserError( __( 'Every field filter must have a key.', 'wp-graphql-gravity-forms' ) );
				}

				$key             = sanitize_text_field( $field_filter['key'] );
				$operator        = $field_filter['operator'] ?? FieldFiltersOperatorInputEnum::IN; // Default to "in".
				$value           = $this->get_field_filter_value( $field_filter, $operator );
				$field_filters[] = compact( 'key', 'operator', 'value' );

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
			FieldFiltersOperatorInputEnum::GREATER_THAN,
			FieldFiltersOperatorInputEnum::LESS_THAN,
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
		if ( ! empty( $this->args['where']['sort'] ) && is_array( $this->args['where']['sort'] ) ) {
			return [
				'key'        => $this->args['where']['sort']['key'] ?? '',
				'direction'  => $this->args['where']['sort']['direction'] ?? 'ASC',
				'is_numeric' => $this->args['where']['sort']['isNumeric'] ?? false,
			];
		}

		return [];
	}

	/**
	 * Get paging arguments for entry ID query.
	 *
	 * @return array
	 */
	private function get_paging() : array {
		$first        = absint( $this->args['first'] ?? 20 );
		$after_cursor = ! empty( $this->args['after'] ) ? json_decode( base64_decode( $this->args['after'] ), true ) : null; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

		/**
		 * @ TODO:
		 * $last  = absint( $this->args['last'] ?? 20 );
		 * $before_cursor = ! empty( $this->args['before'] ) ? json_decode( base64_decode( $this->args['before'] ), true ) : null;
		 */

		return [
			'offset'    => $after_cursor ? $after_cursor['offset'] + $after_cursor['index'] + 1 : 0,
			'page_size' => $first + 1, // Fetch one more to determine if there is a next page.
		];
	}
}
