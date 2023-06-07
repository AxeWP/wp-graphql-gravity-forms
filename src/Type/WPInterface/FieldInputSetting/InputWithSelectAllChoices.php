<?php
/**
 * GraphQL Interface for a FormField with the `select_all_choices_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldInputSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FieldInputSetting\AbstractFieldInputSetting;

/**
 * Class - InputWithSelectAllChoices
 */
class InputWithSelectAllChoices extends AbstractFieldInputSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInputWithSelectAllChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'select_all_choices_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'name' => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when canPrepopulate is `true`.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
