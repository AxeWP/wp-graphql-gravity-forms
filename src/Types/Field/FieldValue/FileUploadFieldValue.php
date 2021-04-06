<?php
/**
 * GraphQL Object Type - FileUploadFieldValue
 * Values for an individual FileUpload field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;

/**
 * Class - FileUploadFieldValue
 */
class FileUploadFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FileUploadFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'File upload field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_properties() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'URL to the uploaded file.', 'wp-graphql-gravity-forms' ),
			],
			/**
			 * Deprecated properties.
			 *
			 * @since 0.4.0
			 */
			'url'   => [
				'type'              => 'String',
				'description'       => __( 'URL to the uploaded file.', 'wp-graphql-gravity-forms' ),
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
		$value = isset( $entry[ $field['id'] ] ) ? (string) $entry[ $field['id'] ] : null;
		return [
			'value' => $value,
			'url'   => $value, // Deprecated @since 0.4.0 .
		];
	}
}
