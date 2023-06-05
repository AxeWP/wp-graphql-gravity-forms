<?php
/**
 * GraphQL Interface for a FormField with the `disable_quantity_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithDisableQuantity
 */
class FieldWithDisableQuantity extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDisableQuantitySetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'disable_quantity_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasQuantity'        => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the field has the quantity property enabled.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => empty( $source->disableQuantity ),
			],
			'isQuantityDisabled' => [
				'type'              => 'Boolean',
				'description'       => __( 'Whether the quantity property should be disabled for this field.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Use `hasQuantity` instead', 'wp-graphql-gravity-forms' ),
				'resolve'           => static fn ( $source ) => ! empty( $source->disableQuantity ),
			],
		];
	}
}
