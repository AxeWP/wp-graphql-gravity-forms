<?php
/**
 * Enum Type - SignatureFieldBorderStyleEnum
 *
 * @package WPGraphQL\GF\Extensions\GFSignature\Type\Enum,
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\Enum;

use WPGraphQL\GF\Type\Enum\AbstractEnum;

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
	const DASHED = 'dashed';
	const DOTTED = 'dotted';
	const DOUBLE = 'double';
	const GROOVE = 'groove';
	const INSET  = 'inset';
	const OUTSET = 'outset';
	const RIDGE  = 'ridge';
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
			'DOUBLE' => [
				'description' => __( 'A "double" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DOUBLE,
			],
			'GROOVE' => [
				'description' => __( 'A "dashed" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::GROOVE,
			],
			'INSET'  => [
				'description' => __( 'An "inset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INSET,
			],
			'OUTSET' => [
				'description' => __( 'An "outset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::OUTSET,
			],
			'RIDGE'  => [
				'description' => __( 'A "ridge" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RIDGE,
			],
			'SOLID'  => [
				'description' => __( 'A "solid" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SOLID,
			],
		];
	}
}
