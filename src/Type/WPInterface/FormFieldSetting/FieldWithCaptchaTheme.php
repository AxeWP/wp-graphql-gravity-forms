<?php
/**
 * GraphQL Interface for a FormField with the `captcha_theme_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\Enum\CaptchaFieldThemeEnum;

/**
 * Class - FieldWithCaptchaTheme
 */
class FieldWithCaptchaTheme extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCaptchaTheme';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'captcha_theme_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'captchaTheme' => [
				'type'        => CaptchaFieldThemeEnum::$type,
				'description' => __( 'Determines the theme to be used for the reCAPTCHA field. Only applicable to the recaptcha captcha type.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn ( $root ) => $root['captchaTheme'] ?: null,
			],
		];
	}
}
