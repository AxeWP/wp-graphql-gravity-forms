<?php
/**
 * Enum Type - NumberFieldFormatEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - NumberFieldFormatEnum
 */
class NumberFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'NumberFieldFormatEnum';

	// Individual elements.
	public const CURRENCY      = 'currency';
	public const DECIMAL_DOT   = 'decimal_dot';
	public const DECIMAL_COMMA = 'decimal_comma';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The format allowed for the number field. .', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'CURRENCY'      => [
				'description' => __( 'Currency format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CURRENCY,
			],
			'DECIMAL_DOT'   => [
				'description' => __( 'Decimal-dot format (e.g. 9,999.99).', 'wp-graphql-gravity-forms' ),
				'value'       => self::DECIMAL_DOT,
			],
			'DECIMAL_COMMA' => [
				'description' => __( 'Decimal-comma format (e.g. 9.999,99).', 'wp-graphql-gravity-forms' ),
				'value'       => self::DECIMAL_COMMA,
			],
		];
	}
}
