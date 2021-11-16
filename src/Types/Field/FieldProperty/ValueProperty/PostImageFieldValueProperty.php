<?php
/**
 * GraphQL Field - PostImageFieldValueProperty
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty\ValueProperty;

use GF_Field;

/**
 * Class - PostImageFieldValueProperty
 */
class PostImageFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'PostImageField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'imageValues';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'PostImage field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string
	 */
	public function get_field_type() : string {
		return PostImageValueProperty::$type;
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
		$value = array_pad( explode( '|:|', $entry[ $field->id ] ), 4, false );

		return [
			'altText'     => $value[4] ?: null,
			'caption'     => $value[2] ?: null,
			'description' => $value[3] ?: null,
			'title'       => $value[1] ?: null,
			'url'         => $value[0] ?: null,
		];
	}
}
