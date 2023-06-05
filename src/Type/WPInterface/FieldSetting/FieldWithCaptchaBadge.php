<?php
/**
 * GraphQL Interface for a FormField with the `captcha_badge_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\CaptchaFieldBadgePositionEnum;

/**
 * Class - FieldWithCaptchaBadge
 */
class FieldWithCaptchaBadge extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCaptchaBadgeSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'captcha_badge_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'captchaBadgePosition' => [
				'type'        => CaptchaFieldBadgePositionEnum::$type,
				'description' => __( 'The language used when the captcha is displayed. This property is available when the captchaType is “captcha”, the default. The possible values are the language codes used by WordPress.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => isset( $source->captchaBadge ) ? $source->captchaBadge : 'bottomright',
			],
		];
	}
}
