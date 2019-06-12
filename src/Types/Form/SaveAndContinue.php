<?php

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Form "Save and Continue" data.
 */
class SaveAndContinue implements Hookable {
    const TYPE = 'SaveAndContinue';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms form Save and Continue data.', 'wp-graphql-gravityforms' ),
            'fields'      => [
                'enabled'   => [
                    'type'        => 'Boolean',
                    'description' => __( 'Whether the Save And Continue feature is enabled.', 'wp-graphql-gravityforms' ),
                ],
                'button'   => [
                    'type'        => FormButton::TYPE,
                    'description' => __( 'Contains the button text. Only applicable when type is set to text.', 'wp-graphql-gravityforms' ),
                ],
            ],
        ] );
    }
}
