<?php
/**
 * Enum Type - CaptchaTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - CaptchaTypeEnum
 */
class CaptchaTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CaptchaTypeEnum';

	// Individual elements.
	const RECAPTCHA = 'recaptcha';
	const SIMPLE    = 'simple_captcha';
	const MATH      = 'math';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'RECAPTCHA' => [
				'description' => __( 'reCAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RECAPTCHA,
			],
			'SIMPLE'    => [
				'description' => __( 'Simple CAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SIMPLE,
			],
			'MATH'      => [
				'description' => __( 'Math CAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::MATH,
			],
		];
	}
}
