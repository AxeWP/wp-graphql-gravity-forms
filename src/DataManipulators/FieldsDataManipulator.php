<?php
/**
 * DataManipulators - FieldsData
 *
 * Manipulates Fields data.
 *
 * @package WPGraphQLGravityForms\DataManipulators
 * @since 0.0.1
 * @since 0.3.0 Set default choices for listField.
 */

namespace WPGraphQLGravityForms\DataManipulators;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\DataManipulator;

/**
 * Class - FieldsDataManipulator
 */
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
		$data = $this->set_list_choice_empty_values( $data );
		$data = $this->add_keys_to_inputs( $data, 'address' );
		$data = $this->add_keys_to_inputs( $data, 'name' );

		return $data;
	}

	/**
	 * Returns Form field with its cssClassList value set.
	 *
	 * @param GF_Field $field Form field.
	 *
	 * @return GF_Field
	 */
	private function set_css_class_list_for_field( GF_Field $field ) : GF_Field {
		$field->cssClassList = array_filter(
			explode( ' ', $field->cssClass ),
			function( $css_class ) {
				return '' !== $css_class;
			}
		);

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
		$fields_to_modify = array_filter(
			$fields,
			function( $field ) {
				return 'address' === $field['type'] || 'name' === $field['type'];
			}
		);

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

	/**
	 * List fields without columns don't have their `choices` key set.
	 * This sets them so we can use the same mutation for both single and multi-column list fields.
	 *
	 * @param array $fields Form fields.
	 *
	 * @return array $fields Form fields with the list `choices` values defined.
	 */
	private function set_list_choice_empty_values( array $fields ) {
		$empty_choices = [
			'text'       => null,
			'value'      => null,
			'isSelected' => null,
			'price'      => null,
		];

		$fields_to_modify = array_filter(
			$fields,
			function( $field ) {
				return 'list' === $field['type'];
			}
		);

		foreach ( $fields_to_modify as $field_index => $field ) {
			if ( empty( $field['choices'] ) ) {
				$fields[ $field_index ]['choices'] = $empty_choices;
			}
		}
		return $fields;
	}

	/**
	 * Add keys to field inputs property.
	 *
	 * @param array  $fields .
	 * @param string $type .
	 * @return array
	 */
	private function add_keys_to_inputs( array $fields, string $type ) : array {
		$input_keys = $this->get_input_keys( $type );

		$fields_to_modify = array_filter(
			$fields,
			function( $field ) use ( $type ) {
				return $type === $field['type'];
			}
		);

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

	/**
	 * Gets keys for field inputs property.
	 *
	 * @param string $type .
	 * @return array
	 */
	private function get_input_keys( string $type ) : array {
		if ( 'address' === $type ) {
			return $this->get_address_input_keys();
		}

		return $this->get_name_input_keys();
	}

	/**
	 * Returns input keys for Address field.
	 *
	 * @return array
	 */
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

	/**
	 * Returns input keys for Name field.
	 *
	 * @return array
	 */
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
