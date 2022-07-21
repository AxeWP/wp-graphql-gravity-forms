<?php
/**
 * GraphQL Interface for a FormField with the `label_placement_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\Enum\FormFieldDescriptionPlacementEnum;
use WPGraphQL\GF\Type\Enum\FormFieldLabelPlacementEnum;

/**
 * Class - FieldWithLabelPlacement
 */
class FieldWithLabelPlacement extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithLabelPlacement';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'label_placement_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'descriptionPlacement' => [
				'type'        => FormFieldDescriptionPlacementEnum::$type,
				'description' => __( 'The placement of the field description.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source->descriptionPlacement ) ? $source->descriptionPlacement : 'inherit';
				},
			],
			'labelPlacement'       => [
				'type'        => FormFieldLabelPlacementEnum::$type,
				'description' => __( 'The field label position.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source ) {
					return ! empty( $source->labelPlacement ) ? $source->labelPlacement : 'inherit';
				},
			],
		];
	}
}
