<?php

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Address field.
 *
 * @see https://docs.gravityforms.com/gf_field_address/
 */
class AddressField extends Field {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'AddressField';

    /**
     * Type registered in Gravity Forms.
     */
    const GF_TYPE = 'address';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms Address field.', 'wp-graphql-gravityforms' ),
            'fields'      => array_merge(
                $this->get_global_properties(),
                FieldProperty\ErrorMessageProperty::get(),
                FieldProperty\InputNameProperty::get(),
                FieldProperty\IsRequiredProperty::get(),
                FieldProperty\SizeProperty::get(),
                FieldProperty\InputsProperty::get(),
                [
                    /**
                     * Possible values: international, us, canadian
                     */
                    'addressType' => [
                        'type'        => 'String',
                        'description' => __('Determines the type of address to be displayed.', 'wp-graphql-gravity-forms'),
                    ],
                    'defaultCountry' => [
                        'type'        => 'String',
                        'description' => __('Contains the country that will be selected by default. Only applicable when "addressType" is set to "international".', 'wp-graphql-gravity-forms'),
                    ],
                    'defaultProvince' => [
                        'type'        => 'String',
                        'description' => __('Contains the province that will be selected by default. Only applicable when "addressType" is set to "canadian".', 'wp-graphql-gravity-forms'),
                    ],
                    'defaultState' => [
                        'type'        => 'String',
                        'description' => __('Contains the state that will be selected by default. Only applicable when "addressType" is set to "us".', 'wp-graphql-gravity-forms'),
                    ],
                    'hideAddress2' => [
                        'type'        => 'Boolean',
                        'description' => __('Legacy property used to control whether the address2 input is visible. To hide the state, use the "isHidden" property of the "inputs" array instead.', 'wp-graphql-gravity-forms'),
                    ],
                    'hideCountry' => [
                        'type'        => 'Boolean',
                        'description' => __('Legacy property used to control whether the country input is visible. To hide the state, use the "isHidden" property of the "inputs" array instead.', 'wp-graphql-gravity-forms'),
                    ],
                    'hideState' => [
                        'type'        => 'Boolean',
                        'description' => __('Legacy property used to control whether the state input is visible. To hide the state, use the "isHidden" property of the "inputs" array instead.', 'wp-graphql-gravity-forms'),
                    ],
                ]
            ),
        ] );
    }
}
