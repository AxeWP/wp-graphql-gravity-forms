<?php

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Error\UserError;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Input\EntriesDateFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesFieldFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesSortingInput;

class RootQueryEntriesConnection implements Hookable, Connection {
    /**
     * The from field name.
     */
    const FROM_FIELD = 'gravityFormsEntries';

    public function register_hooks() {
        add_action('init', [ $this, 'register_connection' ] );
    }

    public function register_connection() {
        register_graphql_connection( [
            'fromType'      => 'RootQuery',
            'toType'        => Entry::TYPE,
            'fromFieldName' => self::FROM_FIELD,
            'connectionArgs' => [
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
                    'description' => __( 'Whether to filter by ALL or ANY of the field filters. Possible values: "all" (default) or "any".', 'wp-graphql-gravity-forms' ),
                ],
                'sort' => [
                    'type'        => EntriesSortingInput::TYPE,
                    'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
                ],
            ],
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) : array {
                /**
                 * Filter to control whether the user should be allowed to view entries.
                 *
                 * @param bool  Whether the current user should be allowed to view form entries.
                 * @param array The form IDs to get entries by.
                 */
                $can_user_view_entries = apply_filters( 'wp_graphql_gf_can_view_entries', current_user_can( 'gravityforms_view_entries' ), $this->get_form_ids( $args ) );

                if ( ! $can_user_view_entries ) {
                    throw new UserError( __( 'Sorry, you are not allowed to view Gravity Forms entries.', 'wp-graphql-gravity-forms' ) );
                }

                return ( new RootQueryEntriesConnectionResolver( $root, $args, $context, $info ) )->get_connection();
            },
        ] );
    }

    private function get_form_ids( array $args ) : array {
        if ( isset( $args['where']['formIds'] ) && is_array( $args['where']['formIds'] ) ) {
            return array_map( 'absint', $args['where']['formIds'] );
        }

        return [];
    }
}
