<?php
/**
 * Enum Type - SignatureFieldBorderWidthEnum
 *
 * @package WPGraphQL\GF\Extensions\GFSignature\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\Enum;

use WPGraphQL\GF\Type\Enum\AbstractEnum;

/**
 * Class - SignatureFieldBorderWidthEnum
 */
class SignatureFieldBorderWidthEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SignatureFieldBorderWidthEnum';

	// Individual elements.
	public const NONE   = '0';
	public const SMALL  = '1';
	public const MEDIUM = '2';
	public const LARGE  = '3';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Width of the border around the signature area.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'NONE'   => [
				'description' => __( 'No border width.', 'wp-graphql-gravity-forms' ),
				'value'       => self::NONE,
			],
			'SMALL'  => [
				'description' => __( 'A small border width.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SMALL,
			],
			'MEDIUM' => [
				'description' => __( 'A medium border width.', 'wp-graphql-gravity-forms' ),
				'value'       => self::MEDIUM,
			],
			'LARGE'  => [
				'description' => __( 'A large border width.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LARGE,
			],
		];
	}
}
