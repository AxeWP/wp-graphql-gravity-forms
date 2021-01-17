<?php

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\Field\AddressField;

/**
 * Values for an individual Address field.
 */
class AddressFieldValue implements Hookable, Type, FieldValue {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = AddressField::TYPE . 'Value';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Gravity Forms address field values.', 'wp-graphql-gravity-forms' ),
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
                    'description' => __( 'City.', 'wp-graphql-gravity-forms' ),
                ],
                'state' => [
                    'type'        => 'String',
                    'description' => __( 'State / province.', 'wp-graphql-gravity-forms' ),
                ],
                'zip' => [
                    'type'        => 'String',
                    'description' => __( 'ZIP / postal code.', 'wp-graphql-gravity-forms' ),
                ],
                'country' => [
                    'type'        => 'String',
                    'description' => __( 'Country.', 'wp-graphql-gravity-forms' ),
                ],
            ],
        ] );
    }

    /**
     * Get the field value.
     *
     * @param array    $entry Gravity Forms entry.
     * @param GF_Field $field Gravity Forms field.
     *
     * @return array Entry field value.
     */
    public static function get( array $entry, GF_Field $field ) : array {
        return [
            'street'  => array_key_exists( $field['inputs'][0]['id'], $entry ) ? $entry[ $field['inputs'][0]['id'] ] : null,
            'lineTwo'  => array_key_exists( $field['inputs'][1]['id'], $entry ) ? $entry[ $field['inputs'][1]['id'] ] : null,
            'city'  => array_key_exists( $field['inputs'][2]['id'], $entry ) ? $entry[ $field['inputs'][2]['id'] ] : null,
            'state'  => array_key_exists( $field['inputs'][3]['id'], $entry ) ? $entry[ $field['inputs'][3]['id'] ] : null,
            'zip'  => array_key_exists( $field['inputs'][4]['id'], $entry ) ? $entry[ $field['inputs'][4]['id'] ] : null,
            'country'  => array_key_exists( $field['inputs'][5]['id'], $entry ) ? $entry[ $field['inputs'][5]['id'] ] : null,
        ];
    }
}
