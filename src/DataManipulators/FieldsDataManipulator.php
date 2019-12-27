<?php

namespace WPGraphQLGravityForms\DataManipulators;

use GF_Field;
use GraphQLRelay\Relay;
use WPGraphQLGravityForms\Interfaces\DataManipulator;
use WPGraphQLGravityForms\Types\Form\Form;

class FieldsDataManipulator implements DataManipulator {
    /**
     * Manipulate form fields data.
     *
     * @param array $data The form fields data to be manipulated.
     *
     * @return array Manipulated form fields data.
     */
    public function manipulate( array $data ) : array {
        $data = array_map( [ $this, 'set_css_class_list_for_field' ], $data );
        $data = $this->set_is_hidden_values( $data );
        $data = $this->add_keys_to_inputs( $data, 'address' );
        $data = $this->add_keys_to_inputs( $data, 'name' );

        return $data;
    }

    /**
     * @param GF_Field $field Form field.
     *
     * @return GF_Field $field Form field with its cssClassList value set.
     */
    private function set_css_class_list_for_field( GF_Field $field ) : GF_Field {
        $field->cssClassList = array_filter( explode( ' ', $field->cssClass ), function( $css_class ) {
            return '' !== $css_class;
        } );

        return $field;
    }

    /**
     * Some field inputs don't always have their 'isHidden' keys set.
     * This makes sure they're always set to true or false.
     *
     * @param array $fields Form fields.
     *
     * @return array $fields Form fields with address 'isHidden' values coerced to booleans.
     */
    private function set_is_hidden_values( array $fields ) : array {
        $fields_to_modify = array_filter( $fields, function( $field ) {
            return 'address' === $field['type'] || 'name' === $field['type'];
        });

        foreach ( $fields_to_modify as $field_index => $field ) {
            /**
             * The inputs are copied, modified, then used to overwrite the original inputs
             * to avoid this error that occurs when trying to modify input keys directly:
             * "indirect modification of overloaded element of <field object>"
             */
            $inputs = $field['inputs'];

            foreach ( $inputs as $input_index => $input ) {
                $inputs[ $input_index ]['isHidden'] = (bool) ( $inputs[ $input_index ]['isHidden'] ?? false );
            }

            $fields[ $field_index ]['inputs'] = $inputs;
        }

        return $fields;
    }

    private function add_keys_to_inputs( array $fields, string $type ) : array {
        $input_keys = $this->get_input_keys( $type );

        $fields_to_modify = array_filter( $fields, function( $field ) use ( $type ) {
            return $type === $field['type'];
        });

        foreach ( $fields_to_modify as $field_index => $field ) {
            /**
             * The inputs are copied, modified, then used to overwrite the original inputs
             * to avoid this error that occurs when trying to modify input keys directly:
             * "indirect modification of overloaded element of <field object>"
             */
            $inputs = $field['inputs'];

            foreach ( $inputs as $input_index => $input ) {
                $inputs[ $input_index ]['key'] = $input_keys[ $input_index ];
            }

            $fields[ $field_index ]['inputs'] = $inputs;
        }

        return $fields;
    }

    private function get_input_keys( string $type ) : array {
        if ( 'address' === $type ) {
            return $this->get_address_input_keys();
        }

        return $this->get_name_input_keys();
    }

    private function get_address_input_keys() {
        return [
            'street',
            'lineTwo',
            'city',
            'state',
            'zip',
            'country',
        ];
    }

    private function get_name_input_keys() {
        return [
            'prefix',
            'first',
            'middle',
            'last',
            'suffix',
        ];
    }
}
