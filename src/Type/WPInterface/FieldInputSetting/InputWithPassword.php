<?php
/**
 * GraphQL Interface for a FormField with the `password_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldInputSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FieldInputSetting\AbstractFieldInputSetting;

/**
 * Class - InputWithPassword
 */
class InputWithPassword extends AbstractFieldInputSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInputWithPasswordSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'password_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'customLabel' => [
				'type'        => 'String',
				'description' => __( 'The custom label for the input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
			],
			'isHidden'    => [
				'type'        => 'Boolean',
				'description' => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
			],
			'placeholder' => [
				'type'        => 'String',
				'description' => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
