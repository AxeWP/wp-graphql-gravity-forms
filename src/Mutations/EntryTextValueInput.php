<?php

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Sorting input type for Entries queries.
 */
class EntryTextValueInput implements Hookable, InputType {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'EntryTextValueInput';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
    }

    public function register_input_type() {
        register_graphql_input_type( self::TYPE, [
            'description' => __('Entry text value.', 'wp-graphql-gravity-forms'),
            'fields'      => [
                'id' => [
                    'type'        => 'Integer',
                    'description' => __( 'The ID of the field.', 'wp-graphql-gravity-forms' ),
                ],
                'value' => [
                    'type'        => 'String',
                    'description' => __( 'The field value.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }
}
