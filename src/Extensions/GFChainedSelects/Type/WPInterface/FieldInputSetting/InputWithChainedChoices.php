<?php
/**
 * GraphQL Interface for choice on a FormField with the `chained_choices_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldInputSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\WPInterface\FieldInputSetting;

use WPGraphQL\GF\Type\WPInterface\FieldInputSetting\AbstractFieldInputSetting;

/**
 * Class - InputWithChainedChoices
 */
class InputWithChainedChoices extends AbstractFieldInputSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldInputWithChainedChoicesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'chained_choices_setting';

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
