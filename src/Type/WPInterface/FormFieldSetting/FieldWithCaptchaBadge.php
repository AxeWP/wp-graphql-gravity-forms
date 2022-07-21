<?php
/**
 * GraphQL Interface for a FormField with the `captcha_badge_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\Enum\CaptchaFieldBadgePositionEnum;

/**
 * Class - FieldWithCaptchaBadge
 */
class FieldWithCaptchaBadge extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCaptchaBadge';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'captcha_badge_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'captchaBadgePosition' => [
				'type'        => CaptchaFieldBadgePositionEnum::$type,
				'description' => __( 'The language used when the captcha is displayed. This property is available when the captchaType is “captcha”, the default. The possible values are the language codes used by WordPress.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => isset( $source->captchaBadge ) ? $source->captchaBadge : 'bottomright',
			],
		];
	}
}
