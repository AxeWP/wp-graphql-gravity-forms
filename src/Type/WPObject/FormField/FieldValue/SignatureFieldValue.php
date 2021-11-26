<?php
/**
 * GraphQL Field - SignatureFieldValue
 * Values for an individual Signature field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use GF_Field_Signature;

/**
 * Class - SignatureFieldValue
 */
class SignatureFieldValue extends AbstractFieldValue {
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
	 * {@inheritDoc}
	 */
	public static function get( array $entry_values, GF_Field $field ) {
		if ( ! class_exists( 'GF_Field_Signature' ) || ! $field instanceof GF_Field_Signature || ! array_key_exists( $field->id, $entry_values ) ) {
			return null;
		}

		return $field->get_value_url( $entry_values[ $field->id ] ) ?: null;
	}
}
