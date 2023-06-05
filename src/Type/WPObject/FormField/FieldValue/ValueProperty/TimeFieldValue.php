<?php
/**
 * GraphQL Object Type - TimeValuePropery
 * An individual property for the 'value' Time field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;

use WPGraphQL\GF\Type\Enum\AmPmEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - TimeValueProperty
 */
class TimeFieldValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'TimeFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The individual properties for each element of the Time value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'displayValue' => [
				'type'        => 'String',
				'description' => __( 'The full display value in 12-hour format. Example: "08:25 am".', 'wp-graphql-gravity-forms' ),
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
				'type'        => AmPmEnum::$type,
				'description' => __( 'AM or PM.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
