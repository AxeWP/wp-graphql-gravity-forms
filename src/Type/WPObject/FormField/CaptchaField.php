<?php
/**
 * GraphQL Object Type - CaptchaField
 *
 * @see https://docs.gravityforms.com/gf_field_captcha/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\Enum\CaptchaThemeEnum;
use WPGraphQL\GF\Type\Enum\CaptchaTypeEnum;
use WPGraphQL\GF\Type\Enum\SizePropertyEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - CaptchaField
 */
class CaptchaField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CaptchaField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'captcha';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms CAPTCHA field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\DescriptionPlacementProperty::get(),
			[
				'captchaLanguage'              => [
					'type'        => 'String',
					'description' => __( 'The language used when the captcha is displayed. This property is available when the captchaType is “captcha”, the default. The possible values are the language codes used by WordPress.', 'wp-graphql-gravity-forms' ),
				],
				'captchaTheme'                 => [
					'type'        => CaptchaThemeEnum::$type,
					'description' => __( 'Determines the theme to be used for the reCAPTCHA field. Only applicable to the recaptcha captcha type.', 'wp-graphql-gravity-forms' ),
					'resolve'     => fn ( $root ) => $root['captchaTheme'] ?: null,
				],
				'captchaType'                  => [
					'type'        => CaptchaTypeEnum::$type,
					'description' => __( 'Determines the type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' ),
					'resolve'     => fn( $root ) => $root['captchaType'] ?: 'recaptcha',
				],
				'simpleCaptchaSize'            => [
					'type'        => SizePropertyEnum::$type,
					'description' => __( 'Determines the CAPTCHA image size. Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
				],
				'simpleCaptchaFontColor'       => [
					'type'        => 'String',
					'description' => __( 'Determines the image’s font color, in HEX format (i.e. #CCCCCC). Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
				],
				'simpleCaptchaBackgroundColor' => [
					'type'        => 'String',
					'description' => __( 'Determines the image’s background color, in HEX format (i.e. #CCCCCC). Only applicable to simple_captcha and math captcha types.', 'wp-graphql-gravity-forms' ),
				],
			],
			... static::get_fields_from_gf_settings()
		);
	}
}
