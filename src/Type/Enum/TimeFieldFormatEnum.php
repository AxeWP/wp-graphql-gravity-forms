<?php
/**
 * Enum Type - TimeFieldFormatEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - TimeFieldFormatEnum
 */
class TimeFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'TimeFieldFormatEnum';

	// Individual elements.
	public const H12 = '12';
	public const H24 = '24';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'How the time is displayed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'H12' => [
				'description' => __( '12-hour time format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::H12,
			],
			'H24' => [
				'description' => __( '24-hour time format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::H24,
			],
		];
	}
}
