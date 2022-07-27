<?php
/**
 * GraphQL Interface for a FormField with the `address_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldInputSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FormFieldInputSetting\AbstractFormFieldInputSetting;

/**
 * Class - InputWithAddress
 */
class InputWithAddress extends AbstractFormFieldInputSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInputWithAddress';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'address_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'autocompleteAttribute' => [
				'type'        => 'String',
				'description' => __( 'The autocomplete attribute for the field.', 'wp-graphql-gravity-forms' ),
			],
			'defaultValue'          => [
				'type'        => 'String',
				'description' => __( 'Contains the default value for the field. When specified, the field\'s value will be populated with the contents of this property when the form is displayed.', 'wp-graphql-gravity-forms' ),
			],
			'customLabel'           => [
				'type'        => 'String',
				'description' => __( 'The custom label for the input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
			],
			'isHidden'              => [
				'type'        => 'Boolean',
				'description' => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
			],
			'key'                   => [
				'type'        => 'String',
				'description' => __( 'Key used to identify this input.', 'wp-graphql-gravity-forms' ),
			],
			'name'                  => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
			'placeholder'           => [
				'type'        => 'String',
				'description' => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
