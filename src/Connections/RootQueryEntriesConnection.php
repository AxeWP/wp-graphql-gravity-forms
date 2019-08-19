<?php

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Entry\EntriesDateFiltersInput;
use WPGraphQLGravityForms\Types\Entry\EntriesFieldFiltersInput;
use WPGraphQLGravityForms\Types\Entry\EntriesSortingInput;

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
                    'description' => __( 'Whether to filter by ALL or ANY of the field filters. Possible values: all (default), any.', 'wp-graphql-gravity-forms' ),
                ],
                'sort' => [
                    'type'        => EntriesSortingInput::TYPE,
                    'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
                ],
            ],
            'resolve' => function( $root, array $args, AppContext $context, ResolveInfo $info ) : array {
                return ( new RootQueryEntriesConnectionResolver( $root, $args, $context, $info ) )->get_connection();
            },
        ] );
    }
}
