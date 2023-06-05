<?php
/**
 * Enum Type - AmPmEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - AmPmEnum
 */
class AmPmEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AmPmEnum';

	// Individual elements.
	public const AM = 'am';
	public const PM = 'pm';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The AM or PM cycle in a 12-hour clock.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'AM' => [
				'description' => __( 'AM. The first 12-hour cycle of the day.', 'wp-graphql-gravity-forms' ),
				'value'       => self::AM,
			],
			'PM' => [
				'description' => __( 'PM. The second 12-hour cycle of the day.', 'wp-graphql-gravity-forms' ),
				'value'       => self::PM,
			],
		];
	}
}
