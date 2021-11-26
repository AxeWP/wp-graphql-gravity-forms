<?php
/**
 * GraphQL Field - PostImageFieldValue
 * Values for an individual Text field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty\PostImageValueProperty;

/**
 * Class - PostImageFieldValue
 */
class PostImageFieldValue extends AbstractFieldValue {
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
	 * {@inheritDoc}
	 */
	public static function get( array $entry_values, GF_Field $field ) : array {
		$value = array_pad( explode( '|:|', $entry_values[ $field->id ] ), 4, false );

		return [
			'altText'     => $value[4] ?: null,
			'caption'     => $value[2] ?: null,
			'description' => $value[3] ?: null,
			'title'       => $value[1] ?: null,
			'url'         => $value[0] ?: null,
		];
	}
}
