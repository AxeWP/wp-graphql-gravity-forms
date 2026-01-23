<?php
/**
 * GraphQL Interface for a FormField with the `password_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldInputSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FieldInputSetting\AbstractFieldInputSetting;

/**
 * Class - InputWithPassword
 */
class InputWithPassword extends AbstractFieldInputSetting implements \WPGraphQL\GF\Interfaces\TypeWithInterfaces {
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
				'description' => static fn () => __( 'The custom label for input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
			],
			'isHidden'    => [
				'type'        => 'Boolean',
				'description' => static fn () => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
			],
			'placeholder' => [
				'type'        => 'String',
				'description' => static fn () => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_interfaces(): array {
		return [
			\WPGraphQL\GF\Type\WPInterface\FieldInput::$type,
		];
	}
}
