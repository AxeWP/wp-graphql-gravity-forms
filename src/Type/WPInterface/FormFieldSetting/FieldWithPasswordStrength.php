<?php
/**
 * GraphQL Interface for a FormField with the `password_strength_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\Enum\PasswordFieldMinStrengthEnum;

/**
 * Class - FieldWithPasswordStrength
 */
class FieldWithPasswordStrength extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPasswordStrength';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'password_strength_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasPasswordStrengthIndicator' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the field displays the password strength indicator.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->passwordStrengthEnabled ),
			],
			'minPasswordStrength'          => [
				'type'        => PasswordFieldMinStrengthEnum::$type,
				'description' => __( 'Indicates how strong the password should be.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
