<?php
/**
 * GraphQL Interface for a FormField with the `conditional_logic_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\WPObject\ConditionalLogic\ConditionalLogic;

/**
 * Class - FieldWithConditionalLogic
 */
class FieldWithConditionalLogic extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithConditionalLogic';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 *
	 * @todo handle _field and _page separately.
	 */
	public static string $field_setting = 'conditional_logic_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'conditionalLogic' => [
				'type'        => ConditionalLogic::$type,
				'description' => __( 'Controls the visibility of the field based on values selected by the user.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
