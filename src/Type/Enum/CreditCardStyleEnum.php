<?php
/**
 * Enum Type - CreditCardStyleEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - CreditCardStyleEnum
 */
class CreditCardStyleEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CreditCardStyleEnum';

	// Individual elements.
	const STANDARD = 'style1';
	const THREE_D  = 'style2';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Type of form confirmation to be used.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values() : array {
		return [
			'3D'       => [
				'description' => __( '3D credit card style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::THREE_D,
			],
			'STANDARD' => [
				'description' => __( 'Standard credit card style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::STANDARD,
			],
		];
	}
}
