<?php
/**
 * GraphQL Object Type - SignatureFieldValue
 * Values for an individual Signature field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 * @since   0.3.0 use $field->get_value_url() to retrieve signature url.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty\SignatureFieldValueProperty;

/**
 * Class - SignatureFieldValue
 */
class SignatureFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SignatureFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'Signature field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The URL to the signature image.', 'wp-graphql-gravity-forms' ),
			],
			/**
			 * Deprecated properties.
			 *
			 * @since 0.4.0
			 */
			'url'   => [
				'type'              => 'String',
				'description'       => __( 'URL to the  file.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `value` instead.', 'wp-graphql-gravity-forms' ),
			],
		];
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
		$value = SignatureFieldValueProperty::get( $entry, $field );
		return [
			'value' => $value,
			'url'   => $value, // Deprecated @since 0.4.0 .
		];
	}
}
