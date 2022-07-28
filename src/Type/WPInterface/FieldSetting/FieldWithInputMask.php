<?php
/**
 * GraphQL Interface for a FormField with the `input_mask_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithInputMask
 */
class FieldWithInputMask extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithInputMask';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'input_mask_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'inputMaskValue' => [
				'type'        => 'String',
				'description' => __( 'The pattern used for the input mask.', 'wp-graphql-gravity-forms' ),
			],
			'hasInputMask'   => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the field has an input mask.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->inputMask ),
			],
		];
	}
}
