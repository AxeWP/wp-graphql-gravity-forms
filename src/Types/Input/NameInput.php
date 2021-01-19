<?php

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Input fields for name field.
 */
class NameInput implements Hookable, InputType {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'NameInput';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
    }

    public function register_input_type() {
        register_graphql_input_type( self::TYPE, [
            'description' => __( 'Input fields for name field.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'prefix' => [
                    'type'        => 'String',
                    'description' => __( 'Prefix, such as Mr., Mrs. etc.', 'wp-graphql-gravity-forms' ),
                ],
                'first' => [
                    'type'        => 'String',
                    'description' => __( 'First name.', 'wp-graphql-gravity-forms' ),
                ],
                'middle' => [
                    'type'        => 'String',
                    'description' => __( 'Middle name.', 'wp-graphql-gravity-forms' ),
                ],
                'last' => [
                    'type'        => 'String',
                    'description' => __( 'Last name.', 'wp-graphql-gravity-forms' ),
                ],
                'suffix' => [
                    'type'        => 'String',
                    'description' => __( 'Suffix, such as Sr., Jr. etc.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }
}
