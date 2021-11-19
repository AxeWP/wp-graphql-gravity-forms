<?php
/**
 * GraphQL Field - SignatureFieldValueProperty
 * Values for an individual Signature field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

use GF_Field;
use GF_Field_Signature;

/**
 * Class - SignatureFieldValueProperty
 */
class SignatureFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'SignatureField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'value';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Signature field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : string {
		return 'String';
	}

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return string|null Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) {
		if ( ! class_exists( 'GF_Field_Signature' ) || ! $field instanceof GF_Field_Signature || ! array_key_exists( $field->id, $entry ) ) {
			return null;
		}

		return $field->get_value_url( $entry[ $field->id ] ) ?: null;
	}
}
