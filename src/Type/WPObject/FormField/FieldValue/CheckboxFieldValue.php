<?php
/**
 * GraphQL Field - CheckboxFieldValue
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty\CheckboxValueProperty;

/**
 * Class - CheckboxFieldValue
 */
class CheckboxFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'CheckboxField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'checkboxValues';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Checkbox field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : array {
		return [ 'list_of' => CheckboxValueProperty::$type ];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		$field_input_ids = wp_list_pluck( $field->inputs, 'id' );
		$checkboxValues  = [];

		foreach ( $field_input_ids as $input_id ) {
			$input_key = array_search( $input_id, array_column( $field->inputs, 'id' ), true );

			$value = ! empty( $entry[ $input_id ] ) ? $entry[ $input_id ] : null;
			$text  = $field->choices[ $input_key ]['text'] ?: $value;

			$checkboxValues[] = [
				'inputId' => $input_id,
				'value'   => $value,
				'text'    => $text,
			];
		}

		return $checkboxValues;
	}
}
