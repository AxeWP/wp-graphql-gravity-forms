<?php

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Input fields for address field.
 */
class AddressInput implements Hookable, InputType {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'AddressInput';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
    }

    public function register_input_type() {
        register_graphql_input_type( self::TYPE, [
            'description' => __( 'Input fields for address field.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'street' => [
                    'type'        => 'String',
                    'description' => __( 'Street address.', 'wp-graphql-gravity-forms' ),
                ],
                'lineTwo' => [
                    'type'        => 'String',
                    'description' => __( 'Address line two.', 'wp-graphql-gravity-forms' ),
                ],
                'city' => [
                    'type'        => 'String',
                    'description' => __( 'Address city.', 'wp-graphql-gravity-forms' ),
                ],
                'state' => [
                    'type'        => 'String',
                    'description' => __( 'Address state/region/province name.', 'wp-graphql-gravity-forms' ),
                ],
                'zip' => [
                    'type'        => 'String',
                    'description' => __( 'Address zip code', 'wp-graphql-gravity-forms' ),
                ],
                'country' => [
                    'type'        => 'String',
                    'description' => __( 'Address country name.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }
}
