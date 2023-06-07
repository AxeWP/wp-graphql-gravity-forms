<?php
/**
 * Enum Type - CaptchaFieldBadgePositionEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - CaptchaFieldBadgePositionEnum
 */
class CaptchaFieldBadgePositionEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CaptchaFieldBadgePositionEnum';

	// Individual elements.
	public const BOTTOM_RIGHT = 'bottomright';
	public const BOTTOM_LEFT  = 'bottomleft';
	public const INLINE       = 'inline';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The position to place the (invisible) reCaptcha badge.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'BOTTOM_LEFT'  => [
				'description' => __( 'Bottom-left position.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BOTTOM_LEFT,
			],
			'BOTTOM_RIGHT' => [
				'description' => __( 'Bottom-right position.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BOTTOM_RIGHT,
			],
			'INLINE'       => [
				'description' => __( 'Inline position.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INLINE,
			],
		];
	}
}
