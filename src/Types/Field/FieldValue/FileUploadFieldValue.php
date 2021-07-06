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
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty\FileUploadFieldValueProperty;

/**
 * Class - FileUploadFieldValue
 */
class FileUploadFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FileUploadFieldValues';

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
	public function get_type_fields() : array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'URL to the uploaded file.', 'wp-graphql-gravity-forms' ),
			],
			/**
			 * Deprecated properties.
			 *
			 * @since 0.6.4
			 */
			'value'  => [
				'type'              => 'String',
				'description'       => __( 'URL to the uploaded file.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `values` instead.', 'wp-graphql-gravity-forms' ),
			],
			'url'    => [
				'type'              => 'String',
				'description'       => __( 'URL to the uploaded file.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `values` instead.', 'wp-graphql-gravity-forms' ),
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
		$value = FileUploadFieldValueProperty::get( $entry, $field );

		return [
			'values' => $value[0] ?? null,
			'value'  => $value[0] ?? null, // Deprecated @since 0.7.0 .
			'url'    => $value[0] ?? null, // Deprecated @since 0.4.0 .
		];
	}
}
