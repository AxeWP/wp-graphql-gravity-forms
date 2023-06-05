<?php
/**
 * Enum Type - RecaptchaTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since   0.11.1
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - RecaptchaTypeEnum
 */
class RecaptchaTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'RecaptchaTypeEnum';

	// Individual elements.
	public const CHECKBOX  = 'checkbox';
	public const INVISIBLE = 'invisible';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Determines which version of reCAPTCHA v2 will be used. ', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'CHECKBOX'  => [
				'description' => __( 'A checkbox reCAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CHECKBOX,
			],
			'INVISIBLE' => [
				'description' => __( 'An invisible reCAPTCHA type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INVISIBLE,
			],
		];
	}
}
