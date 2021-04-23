<?php
/**
 * GraphQL Field - PostCustomFieldValueProperty
 * Values for an individual PostCustom field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - PostCustomFieldValueProperty
 */
class PostCustomFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'PostCustomField';

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
		return __( 'PostCustom field value.', 'wp-graphql-gravity-forms' );
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
	 * @return array|null Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) {
		$values      = $entry[ $field->id ] ?? null;
		$value_array = null;

		if ( empty( $values ) ) {
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
