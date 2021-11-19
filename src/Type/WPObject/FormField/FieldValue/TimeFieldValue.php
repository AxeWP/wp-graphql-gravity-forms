<?php
/**
 * GraphQL Object Type - TimeFieldValue
 * Values for an individual Time field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty\TimeFieldValueProperty;

/**
 * Class - TimeFieldValue
 */
class TimeFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'TimeFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Time field values.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'displayValue' => [
				'type'        => 'String',
				'description' => __( 'The full display value. Example: "08:25 am".', 'wp-graphql-gravity-forms' ),
			],
			'hours'        => [
				'type'        => 'String',
				'description' => __( 'The hours, in this format: hh.', 'wp-graphql-gravity-forms' ),
			],
			'minutes'      => [
				'type'        => 'String',
				'description' => __( 'The minutes, in this format: mm.', 'wp-graphql-gravity-forms' ),
			],
			'amPm'         => [
				'type'        => 'String',
				'description' => __( 'AM or PM.', 'wp-graphql-gravity-forms' ),
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
		return TimeFieldValueProperty::get( $entry, $field );
	}
}
