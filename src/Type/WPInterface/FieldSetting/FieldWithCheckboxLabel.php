<?php
/**
 * GraphQL Interface for a FormField with the `checkbox_label_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithCheckboxLabel
 */
class FieldWithCheckboxLabel extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithCheckboxLabel';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'checkbox_label_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'checkboxLabel' => [
				'type'        => 'String',
				'description' => __( 'Text of the consent checkbox.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
