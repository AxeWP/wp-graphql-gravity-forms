<?php
/**
 * GraphQL Object Type - PhoneFieldValue
 * Values for an individual Phone field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty\PhoneFieldValueProperty;

/**
 * Class - PhoneFieldValue
 */
class PhoneFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PhoneFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Phone field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The value.', 'wp-graphql-gravity-forms' ),
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
		return [ 'value' => PhoneFieldValueProperty::get( $entry, $field ) ];
	}
}
