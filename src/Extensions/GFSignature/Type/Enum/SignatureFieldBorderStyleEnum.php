<?php
/**
 * Enum Type - SignatureFieldBorderStyleEnum
 *
 * @package WPGraphQL\GF\Extensions\GFSignature\Type\Enum,
 * @since   0.10.0
 */

declare( strict_types = 1 );

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
	public const DASHED = 'dashed';
	public const DOTTED = 'dotted';
	public const DOUBLE = 'double';
	public const GROOVE = 'groove';
	public const INSET  = 'inset';
	public const OUTSET = 'outset';
	public const RIDGE  = 'ridge';
	public const SOLID  = 'solid';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Border style to be used around the signature area.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'DOTTED' => [
				'description' => static fn () => __( 'A "dotted" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DOTTED,
			],
			'DASHED' => [
				'description' => static fn () => __( 'A "dashed" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DASHED,
			],
			'DOUBLE' => [
				'description' => static fn () => __( 'A "double" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DOUBLE,
			],
			'GROOVE' => [
				'description' => static fn () => __( 'A "dashed" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::GROOVE,
			],
			'INSET'  => [
				'description' => static fn () => __( 'An "inset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INSET,
			],
			'OUTSET' => [
				'description' => static fn () => __( 'An "outset" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::OUTSET,
			],
			'RIDGE'  => [
				'description' => static fn () => __( 'A "ridge" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RIDGE,
			],
			'SOLID'  => [
				'description' => static fn () => __( 'A "solid" border style.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SOLID,
			],
		];
	}
}
