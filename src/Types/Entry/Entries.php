<?php

namespace WPGraphQLGravityForms\Types\Entry;

use GFAPI;
use GraphQLRelay\Relay;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\Types\Entry\Entry;

/**
 * List of Gravity Forms entries.
 *
 * @see https://docs.gravityforms.com/api-functions/#get-entries
 */
class Entries implements Hookable, Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'GravityFormsEntries';

    /**
     * Field registered in WPGraphQL.
     */
    const FIELD = 'gravityFormsEntries';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
        add_action( 'graphql_register_types', [ $this, 'register_field' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'List of Gravity Forms entries.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'entries' => [
                    'type'        => [ 'list_of' => Entry::TYPE ],
                    'description' => __( 'List of Gravity Forms entries.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }

    public function register_field() {
        register_graphql_field( 'RootQuery', self::FIELD, [
            'description' => __( 'Get a list of Gravity Forms entries.', 'wp-graphql-gravity-forms' ),
            'type' => self::TYPE,
            'args' => [
                'formIds' => [
                    'type'        => [ 'list_of' => 'ID' ],
                    'description' => __( 'Array of form IDs to limit the entries to. Exclude this argument to query all forms.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: Convert to an enum.
                'status' => [
                    'type'        => 'String',
                    'description' => __( 'Entry status. Possible values: "active" (default), "spam", or "trash".', 'wp-graphql-gravity-forms' ),
                ],
                'dateFilters' => [
                    'type'        => EntriesDateFiltersInput::TYPE,
                    'description' => __( 'Date filters to apply.', 'wp-graphql-gravity-forms' ),
                ],
                'fieldFilters' => [
                    'type'        => [ 'list_of' => EntriesFieldFiltersInput::TYPE ],
                    'description' => __( 'Field-specific filters to apply.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: Convert to an enum.
                'fieldFiltersMode' => [
                    'type'        => 'String',
                    'description' => __( 'Whether to filter by ALL or ANY of the field filters. Possible values: all (default), any.', 'wp-graphql-gravity-forms' ),
                ],
                'sort' => [
                    'type'        => EntriesSortingInput::TYPE,
                    'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: Is this needed?
                'totalCount' => [
                    'type'        => 'Integer',
                    'description' => __( 'Whether the total number of entries should be returned.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: pagination.
            ],
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
                if ( empty( $args['formIds'] ) || ! is_array( $args['formIds'] ) ) {
                    throw new UserError( __( 'An array of form IDs must be provided.', 'wp-graphql-gravity-forms' ) );
                }

                $form_ids = $this->get_form_ids_from_global_ids( $args['formIds'] );

                if ( ! $form_ids || count( $args['formIds'] ) !== count( $form_ids ) ) {
                    throw new UserError( __( 'The global form ID(s) provided were not formatted correctly.', 'wp-graphql-gravity-forms' ) );
                }
                
                $entries = GFAPI::get_entries(
                    $form_ids,
                    $this->get_search_criteria( $args ),
                    $this->get_sort( $args ),
                    [],
                    true
                );                

                return [];

                // TODO: Add support for pagination, then pass these args to GF:
                // $paging = [ 'offset' => 0, 'page_size' => 30 ];
            }
        ] );
    }

    /**
     * Get Gravity Forms form IDs from the Relay global IDs provided. Improperly formatted
     * global IDs are assigned a form ID of 0.
     */
    private function get_form_ids_from_global_ids( array $global_form_ids ) : array {
        return array_filter( array_map( function( string $global_form_id ) {
            $id_parts = Relay::fromGlobalId( $global_form_id );
            return  $id_parts['id'] ?? 0;
        }, $global_form_ids ) );
    }

    private function get_search_criteria( array $args ) : array {
        $search_criteria = [];

        if ( ! empty( $args['status'] ) ) {
            $search_criteria['status'] = sanitize_text_field( $args['status'] );
        }

        if ( ! empty( $args['dateFilters']['startDate'] ) ) {
            $search_criteria['start_date'] = sanitize_text_field( $args['dateFilters']['startDate'] );
        }

        if ( ! empty( $args['dateFilters']['endDate'] ) ) {
            $search_criteria['end_date'] = sanitize_text_field( $args['dateFilters']['endDate'] );
        }

        if ( ! empty( $args['fieldFilters'] ) && is_array( $args['fieldFilters'] ) ) {
            $search_criteria['field_filters'] = array_merge(
                [ 'mode' => $args['fieldFiltersMode'] ?? 'all' ],
                $this->format_field_filters( $args['fieldFilters'] )
            );
        }

        return $search_criteria;
    }

    private function format_field_filters( array $field_filters ) : array {
        return array_reduce( $field_filters, function( $field_filters, $field_filter ) {
            $key      = $field_filter['key'];
            $operator = $field_filter['operator'];
            $value    = $this->get_field_filter_value( $field_filter );

            // Convert from camelCase.
            if ( 'notIn' === $operator ) {
                $operator = 'not in';
            }

            // If 'contains' is being used, convert to a scalar value.
            if ( 'contains' === $operator && $value ) {
                $value = $value[0];
            }

            $field_filters[] = compact( 'key', 'operator', 'value' );

            return $field_filters;
        }, [] );
    }

    private function get_field_filter_value( array $field_filter ) : array {
        return array_reduce( ['stringValues', 'intValues', 'floatValues', 'boolValues'], function( $value, $key ) use ( $field_filter ) {
            if ( isset( $field_filter[ $key ] ) ) {
                $value = array_merge( $value, array_map( 'sanitize_text_fields', $field_filter[ $key ] ) );
            }

            return $value;
        }, [] );
    }

    private function get_sort( array $args ) : array {
        if ( ! empty( $args['sorting'] ) && is_array( $args['sorting'] ) ) {
            return [
                'key'        => $args['sorting']['key'] ?? '',
                'direction'  => $args['sorting']['direction'] ?? 'ASC',
                'is_numeric' => $args['sorting']['isNumeric'] ?? false,
            ];
        }

        return [];
    }
}
