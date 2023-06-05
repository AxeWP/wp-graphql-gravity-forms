<?php
/**
 * GraphQL Interface for a FormField with the `password_strength_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\PasswordFieldMinStrengthEnum;

/**
 * Class - FieldWithPasswordStrength
 */
class FieldWithPasswordStrength extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPasswordStrengthSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'password_strength_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasPasswordStrengthIndicator' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the field displays the password strength indicator.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->passwordStrengthEnabled ),
			],
			'minPasswordStrength'          => [
				'type'        => PasswordFieldMinStrengthEnum::$type,
				'description' => __( 'Indicates how strong the password should be.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
