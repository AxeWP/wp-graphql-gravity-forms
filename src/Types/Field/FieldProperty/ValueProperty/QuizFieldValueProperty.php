<?php
/**
 * GraphQL Field - QuizFieldValueProperty
 * Values for an individual Quiz field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.9.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - QuizFieldValueProperty
 */
class QuizFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'QuizField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'values';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Quiz field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return array
	 */
	public function get_field_type() : array {
		return [ 'list_of' => 'String' ];
	}

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 */
	public static function get( array $entry, GF_Field $field ) : ?array {
		// Checkbox values are stored by each individual choice.
		switch ( $field->get_input_type() ) {
			case 'checkbox':
				$values = array_column( CheckboxFieldValueProperty::get( $entry, $field ), 'value' ) ?? null;
				break;
			case 'radio':
				$values = RadioFieldValueProperty::get( $entry, $field ) ?? null;
				break;
			case 'select':
				$values = SelectFieldValueProperty::get( $entry, $field ) ?? null;
				break;
		}

		if ( empty( $values ) || ! class_exists( 'GFQuiz' ) ) {
			return null;
		}

		// Use the choice name, since the value is autogenerated gibberish.
		$actual_value = ( \GFQuiz::get_instance() )->maybe_format_field_values( $values, $field );

		/**
		 * String values are wrapped in an array to preserve a single output type.
		 *
		 * @todo convert once input unions are supported by GraphQL spec.
		 */
		return is_array( $actual_value ) ? $actual_value : [ $actual_value ];
	}
}
