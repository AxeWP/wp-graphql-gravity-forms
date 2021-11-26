<?php
/**
 * GraphQL Field - NameFieldValue
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty\NameValueProperty;

/**
 * Class - NameFieldValue
 */
class NameFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'NameField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'nameValues';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Name field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : string {
		return NameValueProperty::$type;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get( array $entry_values, GF_Field $field ) : array {
		return [
			'prefix' => ! empty( $entry_values[ $field->inputs[0]['id'] ] ) ? $entry_values[ $field->inputs[0]['id'] ] : null,
			'first'  => ! empty( $entry_values[ $field->inputs[1]['id'] ] ) ? $entry_values[ $field->inputs[1]['id'] ] : null,
			'middle' => ! empty( $entry_values[ $field->inputs[2]['id'] ] ) ? $entry_values[ $field->inputs[2]['id'] ] : null,
			'last'   => ! empty( $entry_values[ $field->inputs[3]['id'] ] ) ? $entry_values[ $field->inputs[3]['id'] ] : null,
			'suffix' => ! empty( $entry_values[ $field->inputs[4]['id'] ] ) ? $entry_values[ $field->inputs[4]['id'] ] : null,
		];
	}
}
