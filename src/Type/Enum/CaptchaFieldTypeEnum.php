<?php
/**
 * Enum Type - CaptchaFieldTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - CaptchaFieldTypeEnum
 */
class CaptchaFieldTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CaptchaFieldTypeEnum';

	// Individual elements.
	public const RECAPTCHA = 'recaptcha';
	public const SIMPLE    = 'simple_captcha';
	public const MATH      = 'math';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'RECAPTCHA' => [
				'description' => static fn () => __( 'reCAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RECAPTCHA,
			],
			'SIMPLE'    => [
				'description' => static fn () => __( 'Simple CAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SIMPLE,
			],
			'MATH'      => [
				'description' => static fn () => __( 'Math CAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::MATH,
			],
		];
	}
}
