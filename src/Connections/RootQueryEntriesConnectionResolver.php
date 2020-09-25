<?php

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQLRelay\Connection\ArrayConnection;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQLGravityForms\Data\Loader\EntriesLoader;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersOperatorInputEnum;

class RootQueryEntriesConnectionResolver extends AbstractConnectionResolver {
    /**
     * @return bool Whether query should execute.
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
     * @return array Query arguments.
     */
    public function get_query_args() : array {
        return [];
    }

    /**
     * @return array Query to use for data fetching.
     */
    public function get_query() : array {
        return [];
    }

    /**
     * @param int $id Node ID.
     *
     * @return string Base-64 encoded cursor value.
     */
	protected function get_cursor_for_node( $id ) : string {
        $first        = $this->args['first'] ?? 20;
        $after_cursor = ! empty( $this->args['after'] ) ? json_decode( base64_decode( $this->args['after'] ), true ) : null;
        $index        = array_search( $id, array_keys( $this->nodes ) );

        // TODO
        // $last  = $this->args['last'] ?? 20;
        // $before_cursor = ! empty( $this->args['before'] ) ? json_decode( base64_decode( $this->args['before'] ), true ) : null;

        $cursor = [
            'offset' => $after_cursor ? $after_cursor['offset'] + $after_cursor['index'] + 1 : 0,
            'index'  => $index,
        ];

        return base64_encode( json_encode( $cursor ) );
	}

    /**
	 * get_ids
	 *
	 * Return an array of ids from the query
	 *
	 * Each Query class in WP and potential datasource handles this differently, so each connection
	 * resolver should handle getting the items into a uniform array of items.
	 *
	 * @return array
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

        if ( is_wp_error( $entry_ids ) ) {
            throw new UserError( __( 'An error occurred while trying to get Gravity Forms entries.', 'wp-graphql-gravity-forms' ) );
        }

        return array_map( 'absint', $entry_ids );
    }

    private function get_form_ids() {
        if ( ! empty( $this->args['where']['formIds'] ) && is_array( $this->args['where']['formIds'] ) ) {
            return array_map( 'absint', $this->args['where']['formIds'] );
        }

        return null;
    }

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
                [ 'mode' => $this->args['where']['fieldFiltersMode'] ?? 'all' ],
                $this->format_field_filters( $this->args['where']['fieldFilters'] )
            );
        }

        return $search_criteria;
    }

    private function apply_status_to_search_criteria( array $search_criteria ) : array {
        $status = $this->args['where']['status'] ?? 'active'; // Default to active entries.

        // For all entries, don't add a 'status' value to search criteria.
        if ( 'all' === $status ) {
            return $search_criteria;
        }

        $search_criteria['status'] = sanitize_text_field( $status );

        return $search_criteria;
    }

    private function format_field_filters( array $field_filters ) : array {
        return array_reduce( $field_filters, function( $field_filters, $field_filter ) {
            if ( empty( $field_filter['key'] ) ) {
                throw new UserError( __( 'Every field filter must have a key.', 'wp-graphql-gravity-forms' ) );
            }

            $key             = sanitize_text_field( $field_filter['key'] );
            $operator        = $field_filter['operator'] ?? FieldFiltersOperatorInputEnum::IN; // Default to "in".
            $value           = $this->get_field_filter_value( $field_filter, $operator );
            $field_filters[] = compact( 'key', 'operator', 'value' );

            return $field_filters;
        }, [] );
    }

    /**
     * @param array  $field_filter Field filter.
     * @param string $operator     Operator.
     *
     * @return mixed Filter value.
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

    private function should_field_filter_be_limited_to_single_value( string $operator ) : bool {
        $operators_to_limit = [
            FieldFiltersOperatorInputEnum::CONTAINS,
            FieldFiltersOperatorInputEnum::GREATER_THAN,
            FieldFiltersOperatorInputEnum::LESS_THAN,
        ];

        return in_array( $operator, $operators_to_limit, true );
    }

    private function get_field_filter_value_fields( array $field_filter ) : array {
        return array_values( array_filter( ['stringValues', 'intValues', 'floatValues', 'boolValues'], function( $value_field ) use ( $field_filter ) {
            return ! empty( $field_filter[ $value_field ] );
        } ) );
    }

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

    private function get_paging() : array {
        $first = absint( $this->args['first'] ?? 20 );
        $after_cursor  = ! empty( $this->args['after'] ) ? json_decode( base64_decode( $this->args['after'] ), true ) : null;

        // TODO
        // $last  = absint( $this->args['last'] ?? 20 );
        // $before_cursor = ! empty( $this->args['before'] ) ? json_decode( base64_decode( $this->args['before'] ), true ) : null;

        return [
            'offset'    => $after_cursor ? $after_cursor['offset'] + $after_cursor['index'] + 1 : 0,
            'page_size' => $first + 1, // Fetch one more to determine if there is a next page.
        ];
    }
}
