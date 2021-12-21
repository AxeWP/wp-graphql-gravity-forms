<?php
/**
 * Enum Type - SignatureFieldBorderStyleEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - SignatureFieldBorderStyleEnum
 */
class SignatureFieldBorderStyleEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SignatureFieldBorderStyleEnum';

	// Individual elements.
	const DOTTED = 'dotted';
	const DASHED = 'dashed';
	const GROOVE = 'groove';
	const RIDGE  = 'ridge';
	const INSET  = 'inset';
	const OUTSET = 'outset';
	const DOUBLE = 'double';
	const SOLID  = 'solid';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Border style to be used around the signature area.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values() : array {
		return [
			'DOTTED' => [
				'description' => __( 'A "dotted" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DOTTED,
			],
			'DASHED' => [
				'description' => __( 'A "dashed" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DASHED,
			],
			'RIDGE'  => [
				'description' => __( 'A "ridge" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RIDGE,
			],
			'INSET'  => [
				'description' => __( 'An "inset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INSET,
			],
			'OUTSET' => [
				'description' => __( 'An "outset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::OUTSET,
			],
			'DOUBLE' => [
				'description' => __( 'A "double" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DOUBLE,
			],
			'SOLID'  => [
				'description' => __( 'A "solid" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SOLID,
			],
		];
	}
}
