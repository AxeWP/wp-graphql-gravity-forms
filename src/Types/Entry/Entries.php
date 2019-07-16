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
    // const TYPE = 'GravityFormsEntries';

    /**
     * Field registered in WPGraphQL.
     */
    const FIELD = 'gravityFormsEntries';

    public function register_hooks() {
        // add_action( 'graphql_register_types', [ $this, 'register_type' ] );
        add_action( 'graphql_register_types', [ $this, 'register_field' ] );
    }

    // public function register_type() {
    //     register_graphql_object_type( self::TYPE, [
    //         'description' => __( 'List of Gravity Forms entries.', 'wp-graphql-gravity-forms' ),
    //         'fields'      => [
    //             'entries' => [
    //                 'type'        => [ 'list_of' => Entry::TYPE ],
    //                 'description' => __( 'List of Gravity Forms entries.', 'wp-graphql-gravity-forms' ),
    //             ],
    //         ],
    //     ] );
    // }

    public function register_field() {
        register_graphql_field( 'RootQuery', self::FIELD, [
            'description' => __( 'Get a list of Gravity Forms entries.', 'wp-graphql-gravity-forms' ),
            'type' => self::TYPE,
            'args' => [
                'formIds' => [
                    'type' => [ 'list_of' => 'ID' ],
                    'description' => __( 'Array of form IDs to limit the entries to. Excluding this argument results in querying all forms.', 'wp-graphql-gravity-forms' ),
                ],
                // @TODO: Convert to an enum.
                'status' => [
                    'type' => 'String',
                    'description' => __( 'Entry status.', 'wp-graphql-gravity-forms' ),
                ],
                'startDate' => [
                    'type'        => 'String',
                    'description' => __( 'Start date in Y-m-d H:i:s format.', 'wp-graphql-gravity-forms' ),
                ],
                'endDate' => [
                    'type'        => 'String',
                    'description' => __( 'End date in Y-m-d H:i:s format.', 'wp-graphql-gravity-forms' ),
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
                'sorting' => [
                    'type'        => EntriesSortingInput::TYPE,
                    'description' => __( 'Whether to filter by ALL or ANY of the field filters. Possible values: all (default), any.', 'wp-graphql-gravity-forms' ),
                ],
                'totalCount' => [
                    'type'        => 'Integer',
                    'description' => __( 'Whether to filter by ALL or ANY of the field filters. Possible values: all (default), any.', 'wp-graphql-gravity-forms' ),
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

                $search_criteria = [
                    'status'        => $args['status'] ?? 'active',
                    'start_date'    => $args['startDate'] ?? '',
                    'end_date'      => $args['endDate'] ?? '',
                ];

                if ( $args['fieldFilters'] ) {
                    $search_criteria['field_filters'] = array_merge(
                        [ 'mode' => $args['fieldFiltersMode'] ?? 'all' ],
                        $this->format_field_filters( $args['fieldFilters'] )
                    );
                }

                $sorting = [];
                if ( ! empty( $args['sorting'] ) && is_array( $args['sorting'] ) ) {
                    $sorting = [
                        'key'        => $args['sorting']['key'] ?? '',
                        'direction'  => $args['sorting']['direction'] ?? 'ASC',
                        'is_numeric' => $args['sorting']['isNumeric'] ?? false,
                    ];
                }

                // TODO: Add support for pagination.
                // $paging = [ 'offset' => 0, 'page_size' => 30 ];

                $entries = GFAPI::get_entries( $form_ids, $search_criteria, $sorting, [], true );

                // @Jason - How to map these entries to `Entry`s
                // See: wp-graphql-gravity-forms/src/Types/Entry/EntryForm.php
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

    private function get_search_criteria( $args ) {
        if ( ! isset( $args['status'], $args['startDate'], $args['endDate'], $args['fieldFiltersMode'], $args['fieldFilters'] ) ) {
            return [];
        }

        $search_criteria = [];

        if ( ! empty( $args['status'] ) ) {
            $search_criteria['status'] = sanitize_text_field( $args['status'] );
        }

        if ( ! empty( $args['startDate'] ) ) {
            $search_criteria['startDate'] = sanitize_text_field( $args['startDate'] );
        }

        if ( ! empty( $args['endDate'] ) ) {
            $search_criteria['endDate'] = sanitize_text_field( $args['endDate'] );
        }

        if ( ! empty( $args['fieldFilters'] ) && is_array( $args['fieldFilters'] ) ) {
            // $search_criteria['field_filters']
        }

        if ( $args['fieldFilters'] ) {
            $search_criteria['field_filters'] = array_merge(
                [ 'mode' => $args['fieldFiltersMode'] ?? 'all' ],
                $this->format_field_filters( $args['fieldFilters'] )
            );
        }
    }
}

/*


// Filter by any column in the main table
$search_criteria['field_filters'] = [
    [
        'key'   => 'currency',
        'value' => 'USD',
    ],
    [
        'key'   => 'is_read',
        'value' => true,
    ],
    [
        'key'   => 'created_by',
        'value' => $user_id,
    ],
];

// Filter by Field Values
$search_criteria['field_filters'][] = [
    'key'   => '1',
    'value' => 'gquiz159982170',
];

// Supported operators for scalar values: is/=, isnot, contains
$search_criteria['field_filters'][] = [
    'key'      => '1',
    'operator' => 'contains',
    'value'    => 'Steve'
];

// Supported operators for array values: in/=, not in/!=
$search_criteria['field_filters'][] = [
    'key'      => '1',
    'operator' => 'not in',
    'value'    => [ 'Alex', 'David', 'Dana' ],
];

'key' => 0 to search all keys.

$search_criteria['field_filters']['mode'] = 'all'; // default
$search_criteria['field_filters']['mode'] = 'any';



GraphQL APi will only support these:
in, notIn, contains

In the case of 'contains', only the first value is used. Example: ['Steve'] becomes 'Steve'


Always include total_count in response
*/
