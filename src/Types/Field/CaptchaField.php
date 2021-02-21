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

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - CaptchaField
 */
class CaptchaField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'CaptchaField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'captcha';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms CAPTCHA field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
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
						/**
						 * Possible values: red, white, blackglass, clean
						 */
						'captchaTheme'                 => [
							'type'        => 'String',
							'description' => __( 'Determines the theme to be used for the reCAPTCHA field. Only applicable to the recaptcha captcha type.', 'wp-graphql-gravity-forms' ),
						],
						/**
						 * Possible values: recaptcha, simple_captcha, math
						 */
						'captchaType'                  => [
							'type'        => 'String',
							'description' => __( 'Determines the type of CAPTCHA field to be used.', 'wp-graphql-gravity-forms' ),
						],
						/**
						 * Possible values: small, medium, large
						 */
						'simpleCaptchaSize'            => [
							'type'        => 'String',
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
					Utils::deprecate_property( FieldProperty\AdminLabelProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\AdminOnlyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\VisibilityProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
				),
			]
		);
	}
}
