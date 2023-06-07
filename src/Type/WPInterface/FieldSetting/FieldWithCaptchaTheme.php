<?php
/**
 * GraphQL Interface for a FormField with the `captcha_theme_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\CaptchaFieldThemeEnum;

/**
 * Class - FieldWithCaptchaTheme
 */
class FieldWithCaptchaTheme extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCaptchaThemeSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'captcha_theme_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'captchaTheme' => [
				'type'        => CaptchaFieldThemeEnum::$type,
				'description' => __( 'Determines the theme to be used for the reCAPTCHA field. Only applicable to the recaptcha captcha type.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $root ) => $root['captchaTheme'] ?: null,
			],
		];
	}
}
