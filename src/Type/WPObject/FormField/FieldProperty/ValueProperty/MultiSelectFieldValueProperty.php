<?php
/**
 * GraphQL Field - MultiSelectFieldValueProperty
 * Values for an individual MultiSelect field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - MultiSelectFieldValueProperty
 */
class MultiSelectFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'MultiSelectField';

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
		return __( 'MultiSelect field value.', 'wp-graphql-gravity-forms' );
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
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array|null Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) {
		$values      = $entry[ $field->id ] ?: null;
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
