<?php
/**
 * GraphQL Interface for a FormField with the `enable_enhanced_ui_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithEnhancedUI
 */
class FieldWithEnhancedUI extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithEnhancedUISetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'enable_enhanced_ui_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasEnhancedUI' => [
				'type'        => 'Boolean',
				'description' => __( 'When set to true, the "Chosen" jQuery script will be applied to this field, enabling search capabilities to Drop Down fields and a more user-friendly interface for Multi Select fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->enableEnhancedUI ),
			],
		];
	}
}
