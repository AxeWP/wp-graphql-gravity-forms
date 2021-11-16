<?php
/**
 * Enum Type - PhoneFieldFormatEnum
 *
 * @package WPGraphQL\GF\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Types\Enum;

/**
 * Class - PhoneFieldFormatEnum
 */
class PhoneFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PhoneFieldFormatEnum';

	// Individual elements.
	const STANDARD      = 'standard';
	const INTERNATIONAL = 'international';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Tthe allowed format for phone numbers.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function get_values() : array {
		return [
			'STANDARD'      => [
				'description' => __( 'Standard phone number format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::STANDARD,
			],
			'INTERNATIONAL' => [
				'description' => __( 'International phone number format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INTERNATIONAL,
			],
		];
	}
}
