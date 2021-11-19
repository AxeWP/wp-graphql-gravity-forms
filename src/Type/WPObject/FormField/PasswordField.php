<?php
/**
 * GraphQL Object Type - PasswordField
 *
 * @see https://docs.gravityforms.com/gf_field_password/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\Enum\MinPasswordStrengthEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - PasswordField
 */
class PasswordField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PasswordField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'password';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Password field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			static::get_global_properties(),
			static::get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\PlaceholderProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\SubLabelPlacementProperty::get(),
			[
				'inputs'                  => [
					'type'        => [ 'list_of' => FieldProperty\PasswordInputProperty::$type ],
					'description' => __( 'Individual properties for each element of the password field.', 'wp-graphql-gravity-forms' ),
				],
				'minPasswordStrength'     => [
					'type'        => MinPasswordStrengthEnum::$type,
					'description' => __( 'Indicates how strong the password should be.', 'wp-graphql-gravity-forms' ),
				],
				'passwordStrengthEnabled' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates whether the field displays the password strength indicator.', 'wp-graphql-gravity-forms' ),
				],
			],
			/**
			* Deprecated field properties.
			*
			* @since 0.2.0
			*/

			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\VisibilityProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
