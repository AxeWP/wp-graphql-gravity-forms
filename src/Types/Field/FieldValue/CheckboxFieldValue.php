<?php
/**
 * GraphQL Object Type - CheckboxFieldValue
 * Value for a checkbox field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;

/**
 * Value for a checkbox field.
 */
class CheckboxFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'Checkbox field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 * @return array
	 */
	public function get_properties() : array {
		return [
			'checkboxValues' => [
				'type'        => [ 'list_of' => CheckboxInputValue::$type ],
				'description' => __( 'Values.', 'wp-graphql-gravity-forms' ),
			],
		];
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

		foreach ( $entry as $input_id => $value ) {
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
