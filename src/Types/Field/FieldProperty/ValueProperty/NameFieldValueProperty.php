<?php
/**
 * GraphQL Field - NameFieldValueProperty
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - NameFieldValueProperty
 */
class NameFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'NameField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'nameValues';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Name field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string
	 */
	public function get_field_type() : string {
		return NameValueProperty::$type;
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
			'prefix' => ! empty( $entry[ $field->inputs[0]['id'] ] ) ? $entry[ $field->inputs[0]['id'] ] : null,
			'first'  => ! empty( $entry[ $field->inputs[1]['id'] ] ) ? $entry[ $field->inputs[1]['id'] ] : null,
			'middle' => ! empty( $entry[ $field->inputs[2]['id'] ] ) ? $entry[ $field->inputs[2]['id'] ] : null,
			'last'   => ! empty( $entry[ $field->inputs[3]['id'] ] ) ? $entry[ $field->inputs[3]['id'] ] : null,
			'suffix' => ! empty( $entry[ $field->inputs[4]['id'] ] ) ? $entry[ $field->inputs[4]['id'] ] : null,
		];
	}
}
