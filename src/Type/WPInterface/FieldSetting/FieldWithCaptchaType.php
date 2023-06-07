<?php
/**
 * GraphQL Interface for a FormField with the `captcha_type_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\CaptchaFieldTypeEnum;

/**
 * Class - FieldWithCaptchaType
 */
class FieldWithCaptchaType extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCaptchaTypeSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'captcha_type_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'captchaType' => [
				'type'        => CaptchaFieldTypeEnum::$type,
				'description' => __( 'Determines the type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $root ) => $root['captchaType'] ?: 'recaptcha',
			],
		];
	}
}
