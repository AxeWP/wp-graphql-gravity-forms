<?php
/**
 * GraphQL Field - FileUploadFieldValue
 * Values for an individual FileUpload field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FileUploadFieldValue
 */
class FileUploadFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $type = 'FileUploadField';

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
		return __( 'FileUpload field value.', 'wp-graphql-gravity-forms' );
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
		$values = $entry_values[ $field->id ] ?: null;

		if ( null === $values ) {
			return $values;
		}

		$values = Utils::maybe_decode_json( $values );

		return ! $values ? null : $values;
	}
}
