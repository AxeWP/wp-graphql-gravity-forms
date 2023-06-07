<?php
/**
 * GraphQL Object Type - ReCAPTCHA Settings
 *
 * @package WPGraphQL\GF\Type\WPObject\Settings
 * @since   0.11.1
 */

namespace WPGraphQL\GF\Type\WPObject\Settings;

use WPGraphQL\GF\Type\Enum\RecaptchaTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - SettingsRecaptcha
 */
class SettingsRecaptcha extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfSettingsRecaptcha';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms reCAPTCHA Settings.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'publicKey' => [
				'type'        => 'String',
				'description' => __( 'The public reCAPTCHA site key.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): ?string => get_option( 'rg_gforms_captcha_public_key', null ),
			],
			'type'      => [
				'type'        => RecaptchaTypeEnum::$type,
				'description' => __( 'The type of of reCAPTCHA v2 to be used', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn (): string => get_option( 'rg_gforms_captcha_type', 'checkbox' ),
			],
		];
	}
}
