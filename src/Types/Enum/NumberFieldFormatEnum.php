<?php
/**
 * Enum Type - NumberFieldFormatEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - NumberFieldFormatEnum
 */
class NumberFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NumberFieldFormatEnum';

	// Individual elements.
	const CURRENCY      = 'currency';
	const DECIMAL_DOT   = 'decimal_dot';
	const DECIMAL_COMMA = 'decimal_comma';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'The format allowed for the number field. ', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
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
