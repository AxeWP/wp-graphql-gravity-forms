<?php
/**
 * GraphQL Object Type - CaptchaField
 *
 * @see https://docs.gravityforms.com/gf_field_captcha/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Enum\CaptchaThemeEnum;
use WPGraphQLGravityForms\Types\Enum\CaptchaTypeEnum;
use WPGraphQLGravityForms\Types\Enum\SizePropertyEnum;
use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - CaptchaField
 */
class CaptchaField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CaptchaField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'captcha';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms CAPTCHA field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\SizeProperty::get(),
			[
				'captchaLanguage'              => [
					'type'        => 'String',
					'description' => __( 'The language used when the captcha is displayed. This property is available when the captchaType is “captcha”, the default. The possible values are the language codes used by WordPress.', 'wp-graphql-gravity-forms' ),
				],
				'captchaTheme'                 => [
					'type'        => CaptchaThemeEnum::$type,
					'description' => __( 'Determines the theme to be used for the reCAPTCHA field. Only applicable to the recaptcha captcha type.', 'wp-graphql-gravity-forms' ),
				],
				'captchaType'                  => [
					'type'        => CaptchaTypeEnum::$type,
					'description' => __( 'Determines the type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' ),
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
			/**
			* Deprecated field properties.
			*
			* @since 0.2.0
			*/

			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AdminLabelProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AdminOnlyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\VisibilityProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
