<?php
/**
 * Enum Type - AddressFieldTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - AddressFieldTypeEnum
 */
class AddressFieldTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressFieldTypeEnum';

	// Individual elements.
	public const INTERNATIONAL = 'international';
	public const US            = 'us';
	public const CANADIAN      = 'canadian';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Determines the type of address to be displayed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'INTERNATIONAL' => [
				'description' => __( 'International address type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INTERNATIONAL,
			],
			'US'            => [
				'description' => __( 'United States address type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::US,
			],
			'CANADA'        => [
				'description' => __( 'Canada address type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CANADIAN,
			],
		];
	}
}
