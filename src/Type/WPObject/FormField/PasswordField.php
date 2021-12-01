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
			FieldProperty\DescriptionPlacementProperty::get(),
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
			static::get_fields_from_gf_settings(),
		);
	}
}
