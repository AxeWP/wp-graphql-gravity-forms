<?php
/**
 * GraphQL Field - PostCustomFieldValue
 * Values for an individual PostCustom field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;

/**
 * Class - PostCustomFieldValue
 */
class PostCustomFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'PostCustomField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'values';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'PostCustom field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : array {
		return [ 'list_of' => 'String' ];
	}

	/**
	 * Get the field value.
	 *
	 * @param array    $entry_values Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array|null Entry field value.
	 */
	public static function get( array $entry_values, GF_Field $field ) {
		$values      = $entry_values[ $field->id ] ?: null;
		$value_array = null;

		if ( null === $values ) {
			return null;
		}

		// If the retrieved value is a string, try converting it from JSON to array.
		if ( is_string( $values ) ) {
			$value_array = json_decode( $values );
			if ( 0 !== json_last_error() ) {
				$value_array = [ $values ];
			}
		}

		return $value_array;
	}
}
