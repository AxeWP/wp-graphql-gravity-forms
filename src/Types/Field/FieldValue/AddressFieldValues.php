<?php

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;

/**
 * An individual value for the Address field.
 */
class AddressFieldValues implements Hookable, Type, FieldValue {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = 'AddressFieldValues';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __('Gravity Forms address field values.', 'wp-graphql-gravity-forms'),
            'fields'      => [
                'values' => [
                    'type'        => [ 'list_of' => AddressFieldValue::TYPE ],
                    'description' => __('Individual address field values.', 'wp-graphql-gravity-forms'),
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
        $values = [];

        foreach ( ['street', 'street2', 'city', 'state', 'zip', 'country'] as $index => $key ) {
            $values[] = [
                'inputId' => $field['inputs'][ $index ]['id'],
                'label'   => $field['inputs'][ $index ]['label'],
                'key'     => $key,
                'value'   => $entry[ $field['inputs'][ $index ]['id'] ],
            ];
        }

        return compact( 'values' );
    }
}
