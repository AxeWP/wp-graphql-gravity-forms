<?php
/**
 * GraphQL Field - SignatureFieldValueProperty
 * Values for an individual Signature field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

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
	public static $type = 'SignatureField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'value';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Signature field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string
	 */
	public function get_field_type() : string {
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
		$value = $field->get_value_url( $entry[ $field->id ] ) ?: null;
		return $value;
	}
}
