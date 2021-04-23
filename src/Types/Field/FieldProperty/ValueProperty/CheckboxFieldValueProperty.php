<?php
/**
 * GraphQL Field - CheckboxFieldValueProperty
 * Values for an individual Text field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - CheckboxFieldValueProperty
 */
class CheckboxFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'checkboxValues';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Checkbox field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return array
	 */
	public function get_field_type() : array {
		return [ 'list_of' => CheckboxValueProperty::$type ];
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

		foreach ( $field_input_ids as $input_id ) {
			$checkboxValues[] = [
				'inputId' => $input_id,
				'value'   => ! empty( $entry[ $input_id ] ) ? $entry[ $input_id ] : null,
			];
		}

		return $checkboxValues;
	}
}
