<?php
/**
 * GraphQL Interface for a FormField with the `select_all_choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldInputSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FormFieldInputSetting\AbstractFormFieldInputSetting;

/**
 * Class - InputWithSelectAllChoices
 */
class InputWithSelectAllChoices extends AbstractFormFieldInputSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInputWithSelectAllChoices';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'select_all_choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'name' => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
