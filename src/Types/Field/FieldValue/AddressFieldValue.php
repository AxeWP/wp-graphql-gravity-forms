<?php

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * An individual value for the Address field.
 */
class AddressFieldValue implements Hookable, Type {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'AddressFieldValue';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __('Gravity Forms address field value.', 'wp-graphql-gravity-forms'),
            'fields'      => [
                'inputId' => [
                    'type'        => 'String',
                    'description' => __('The value\'s input ID.', 'wp-graphql-gravity-forms'),
                ],
                'label' => [
                    'type'        => 'String',
                    'description' => __('Label for value', 'wp-graphql-gravity-forms'),
                ],
                'key' => [
                    'type'        => 'String',
                    'description' => __('Key used to identify this value.', 'wp-graphql-gravity-forms'),
                ],
                'value' => [
                    'type'        => 'String',
                    'description' => __('The value.', 'wp-graphql-gravity-forms'),
                ],
            ],
        ] );
    }
}
