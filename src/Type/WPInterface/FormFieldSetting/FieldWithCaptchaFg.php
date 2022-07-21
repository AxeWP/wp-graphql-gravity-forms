<?php
/**
 * GraphQL Interface for a FormField with the `captcha_fg_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithCaptchaFg
 */
class FieldWithCaptchaFg extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCaptchaFg';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'captcha_fg_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'simpleCaptchaFontColor' => [
				'type'        => 'String',
				'description' => __( 'Determines the image’s font color, in HEX format (i.e. #CCCCCC). Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
