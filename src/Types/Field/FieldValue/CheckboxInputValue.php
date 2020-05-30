<?php

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Value for a single input within a checkbox field.
 */
class CheckboxInputValue implements Hookable, Type {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'CheckboxInputValue';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Value for a single input within a checkbox field.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'inputId' => [
                    'type'        => 'Float',
                    'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
                ],
                'value' => [
                    'type'        => 'String',
                    'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }
}
