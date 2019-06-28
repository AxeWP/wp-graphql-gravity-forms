<?php

namespace WPGraphQLGravityForms\Types\Entry;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Entry field value.
 */
class FieldValue implements Hookable {
    const TYPE = 'FieldValue';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms entry field value.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'key'   => [
                    'type'        => 'String',
                    'description' => __( 'Field key.', 'wp-graphql-gravity-forms' ),
                ],
                'value'   => [
                    'type'        => 'String',
                    'description' => __( 'Field value.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }
}
