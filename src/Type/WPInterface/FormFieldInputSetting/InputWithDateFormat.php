<?php
/**
 * GraphQL Interface for a FormField with the `date_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldInputSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FormFieldInputSetting\AbstractFormFieldInputSetting;

/**
 * Class - InputWithDateFormat
 */
class InputWithDateFormat extends AbstractFormFieldInputSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInputWithDateFormat';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'date_format_setting';

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
			'placeholder'           => [
				'type'        => 'String',
				'description' => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
