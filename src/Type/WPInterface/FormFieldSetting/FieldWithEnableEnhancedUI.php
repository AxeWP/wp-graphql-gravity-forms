<?php
/**
 * GraphQL Interface for a FormField with the `enable_enhanced_ui_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithEnableEnhancedUI
 */
class FieldWithEnableEnhancedUI extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithEnableEnhancedUI';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'enable_enhanced_ui_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasEnhancedUI' => [
				'type'        => 'Boolean',
				'description' => __( 'When set to true, the "Chosen" jQuery script will be applied to this field, enabling search capabilities to Drop Down fields and a more user-friendly interface for Multi Select fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->enableEnhancedUI ),
			],
		];
	}
}
