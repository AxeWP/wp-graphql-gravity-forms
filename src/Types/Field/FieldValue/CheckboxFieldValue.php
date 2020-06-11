<?php

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\Field\CheckboxField;

/**
 * Value for a checkbox field.
 */
class CheckboxFieldValue implements Hookable, Type, FieldValue {
    /**
     * Type registered in WPGraphQL.
     */
    const TYPE = CheckboxField::TYPE . 'Value';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_type' ] );
    }

    public function register_type() {
        register_graphql_object_type( self::TYPE, [
            'description' => __( 'Checkbox field value.', 'wp-graphql-gravity-forms' ),
            'fields'      => [
                'checkboxValues' => [
                    'type'        => [ 'list_of' => CheckboxInputValue::TYPE ],
                    'description' => __( 'Values.', 'wp-graphql-gravity-forms' ),
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
        $field_input_ids = wp_list_pluck( $field->inputs, 'id' );
        $checkboxValues  = [];

        foreach( $entry as $input_id => $value ) {
            $is_field_input_value = in_array( $input_id, $field_input_ids, true ) && '' !== $value;

            if ( $is_field_input_value ) {
                $checkboxValues[] = [
                    'inputId' => $input_id,
                    'value'   => $value,
                ];
            }
        }

        return compact( 'checkboxValues' );
    }
}
