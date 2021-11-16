<?php
/**
 * Enum Type - CaptchaThemeEnum
 *
 * @package WPGraphQL\GF\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Types\Enum;

/**
 * Class - CaptchaThemeEnum
 */
class CaptchaThemeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CaptchaThemeEnum';

	// Individual elements.
	const DARK  = 'dark';
	const LIGHT = 'light';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'The theme to be used for the reCAPTCHA field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function get_values() : array {
		return [
			'LIGHT' => [
				'description' => __( 'Light reCAPTCHA theme.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LIGHT,
			],
			'DARK'  => [
				'description' => __( 'Dark reCAPTCHA theme.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DARK,
			],
		];
	}
}
