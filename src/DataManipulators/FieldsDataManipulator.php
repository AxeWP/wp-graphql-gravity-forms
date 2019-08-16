<?php

namespace WPGraphQLGravityForms\DataManipulators;

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
        $data = $this->set_is_hidden_values( $data );
        $data = $this->add_keys_to_inputs( $data, 'address' );
        $data = $this->add_keys_to_inputs( $data, 'name' );

        return $data;
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

    // /**
    //  * @param array $fields Form fields.
    //  *
    //  * @return array $fields Form fields with keys added to address field inputs.
    //  */
    // private function add_keys_to_address_inputs( array $fields ) : array {
    //     /**
    //      * For Gravity Forms address fields, the first input is always street,
    //      * the second is always lineTwo, and so on. These keys are added to make
    //      * it easy to match up address inputs with user-submitted values.
    //      */
    //     $input_keys = [
    //         'street',
    //         'lineTwo',
    //         'city',
    //         'state',
    //         'zip',
    //         'country',
    //     ];

    //     $name_fields = array_filter( $fields, function( $field ) {
    //         return 'address' === $field['type'];
    //     });

    //     foreach ( $name_fields as $field_index => $field ) {
    //         /**
    //          * The inputs are copied, modified, then re-saved to $field['inputs'] to avoid
    //          * this error that occurs when trying to modify input keys directly:
    //          * "indirect modification of overloaded element of GF_Field_"
    //          */
    //         $inputs = $field['inputs'];

    //         foreach ( $inputs as $input_index => $input ) {
    //             $key = $input_keys[ $input_index ];

    //             $inputs[ $input_index ]['key'] = $key;
    //         }

    //         $fields[ $field_index ]['inputs'] = $inputs;
    //     }

    //     return $fields;
    // }

    // /**
    //  * @param array $fields Form fields.
    //  *
    //  * @return array $fields Form fields with keys added to name field inputs.
    //  */
    // private function add_keys_to_address_inputs( array $fields ) : array {
    //     /**
    //      * For Gravity Forms name fields, the first input is always prefix,
    //      * the second is always first, and so on. These keys are added to make
    //      * it easy to match up name inputs with user-submitted values.
    //      */
    //     $input_keys = [
    //         'prefix',
    //         'first',
    //         'middle',
    //         'last',
    //         'suffix',
    //     ];

    //     $address_fields = array_filter( $fields, function( $field ) {
    //         return 'name' === $field['type'];
    //     });

    //     foreach ( $address_fields as $field_index => $field ) {
    //         /**
    //          * The inputs are copied, modified, then re-saved to $field['inputs'] to avoid
    //          * this error that occurs when trying to modify input keys directly:
    //          * "indirect modification of overloaded element of GF_Field_Address"
    //          */
    //         $inputs = $field['inputs'];

    //         foreach ( $inputs as $input_index => $input ) {
    //             $key = $input_keys[ $input_index ];

    //             $inputs[ $input_index ]['key'] = $key;
    //         }

    //         $fields[ $field_index ]['inputs'] = $inputs;
    //     }

    //     return $fields;
    // }
}
