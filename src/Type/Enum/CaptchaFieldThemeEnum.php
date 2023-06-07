<?php
/**
 * Enum Type - CaptchaFieldThemeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - CaptchaFieldThemeEnum
 */
class CaptchaFieldThemeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CaptchaFieldThemeEnum';

	// Individual elements.
	public const DARK  = 'dark';
	public const LIGHT = 'light';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The theme to be used for the reCAPTCHA field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'LIGHT' => [
				'description' => __( 'Light reCAPTCHA theme.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LIGHT,
			],
			'DARK'  => [
				'description' => __( 'Dark reCAPTCHA theme.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DARK,
			],
		];
	}
}
