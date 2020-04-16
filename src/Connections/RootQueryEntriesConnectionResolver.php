<?php

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQLRelay\Connection\ArrayConnection;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;

class RootQueryEntriesConnectionResolver extends AbstractConnectionResolver {
    /**
     * @return bool Whether query should execute.
     */
    public function should_execute() : bool {
        return current_user_can( 'gravityforms_view_entries' );
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
    public function is_valid_offset( $offset ) : bool {
        return true;
    }

    /**
     * @return array Query arguments.
     */
    public function get_query_args() : array {
        return [];
    }

    /**
     * @param array $node The node.
     * @param null  $key  Unused arg.
     *
     * @return string Base-64 encoded cursor value.
     */
	protected function get_cursor_for_node( $node, $key = null ) : string {
        $first = $this->args['first'] ?? 20;
        $after_cursor  = ! empty( $this->args['after'] ) ? json_decode( base64_decode( $this->args['after'] ), true ) : null;

        // TODO
        // $last  = $this->args['last'] ?? 20;
        // $before_cursor = ! empty( $this->args['before'] ) ? json_decode( base64_decode( $this->args['before'] ), true ) : null;

        $cursor = [
            'offset' => $after_cursor ? $after_cursor['offset'] + $after_cursor['index'] + 1 : 0,
            'index'  => $key,
        ];

        return base64_encode( json_encode( $cursor ) );
	}

    /**
     * @return array Query to use for data fetching.
     */
    public function get_query() : array {
        return [];
    }

    /**
     * @return array The fields for this Gravity Forms entry.
     */
    public function get_items() : array {
        if ( isset( $this->args['last'] ) || isset( $this->args['before'] ) ) {
            throw new UserError( __( 'Sorry, last/before pagination is currently not supported.', 'wp-graphql-gravity-forms' ) );
        }

        $entries = GFAPI::get_entries(
            $this->get_form_ids(),
            $this->get_search_criteria(),
            $this->get_sort(),
            $this->get_paging(),
            $total_overall
        );

        // TODO: is $total_overall needed here?

        if ( is_wp_error( $entries ) ) {
            throw new UserError( __( 'An error occurred while trying to get Gravity Forms entries.', 'wp-graphql-gravity-forms' ) );
        }

        $entry_data_manipulator = new EntryDataManipulator();

        return array_map( function( $entry ) use ( $entry_data_manipulator ) {
            return $entry_data_manipulator->manipulate( $entry );
        }, $entries );
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

            $key      = sanitize_text_field( $field_filter['key'] );
            $operator = $this->get_field_filter_operator( $field_filter );

            if ( ! $this->is_field_filter_operator_valid( $operator ) ) {
                throw new UserError( __( 'Every field filter must have a valid operator.', 'wp-graphql-gravity-forms' ) );
            }

            $value = $this->get_field_filter_value( $field_filter, $operator );

            // If 'contains' is being used, convert to a scalar value.
            if ( 'contains' === $operator ) {
                $value = $value[0];
            }

            $field_filters[] = compact( 'key', 'operator', 'value' );

            return $field_filters;
        }, [] );
    }

    private function get_field_filter_operator( array $field_filter ) : string {
        $operator = $field_filter['operator'] ?? 'in';

        // Convert from camelCase.
        if ( 'notIn' === $operator ) {
            $operator = 'not in';
        }

        return $operator;
    }

    private function is_field_filter_operator_valid( string $operator ) : bool {
        return in_array( $operator, ['in', 'not in', 'contains'], true );
    }

    private function get_field_filter_value( array $field_filter, string $operator ) : array {
        $value_fields = $this->get_field_filter_value_fields( $field_filter );

        if ( 1 !== count( $value_fields ) ) {
            throw new UserError( __( 'Every field filter must have one value field.', 'wp-graphql-gravity-forms' ) );
        }

        return array_map( 'sanitize_text_field', $field_filter[ $value_fields[0] ] );
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
