<?php
/**
 * GraphQL Field - FileUploadFieldValueProperty
 * Values for an individual FileUpload field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use GF_Field;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - FileUploadFieldValueProperty
 */
class FileUploadFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'FileUploadField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'values';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'FileUpload field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return array
	 */
	public function get_field_type() : array {
		return [ 'list_of' => 'String' ];
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
		$values = $entry[ $field->id ] ?: null;

		if ( null === $values ) {
			return $values;
		}

		$values = Utils::maybe_decode_json( $values );

		return ! $values ? null : $values;
	}
}
