<?php
/**
 * GraphQL Field - PostImageFieldValueProperty
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

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
	public static string $type = 'PostImageField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'imageValues';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'PostImage field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : string {
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
