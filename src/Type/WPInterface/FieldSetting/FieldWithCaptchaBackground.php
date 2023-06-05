<?php
/**
 * GraphQL Interface for a FormField with the `captcha_bg_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithCaptchaBackground
 */
class FieldWithCaptchaBackground extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCaptchaBackgroundSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'captcha_bg_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'simpleCaptchaBackgroundColor' => [
				'type'        => 'String',
				'description' => __( 'Determines the image’s background color, in HEX format (i.e. #CCCCCC). Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
