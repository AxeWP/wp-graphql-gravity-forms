<?php
/**
 * Enum Type - PhoneFieldFormatEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - PhoneFieldFormatEnum
 */
class PhoneFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PhoneFieldFormatEnum';

	// Individual elements.
	public const STANDARD      = 'standard';
	public const INTERNATIONAL = 'international';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Tthe allowed format for phone numbers.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
