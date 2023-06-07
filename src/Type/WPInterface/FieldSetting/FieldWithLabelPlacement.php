<?php
/**
 * GraphQL Interface for a FormField with the `label_placement_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\FormFieldDescriptionPlacementEnum;
use WPGraphQL\GF\Type\Enum\FormFieldLabelPlacementEnum;

/**
 * Class - FieldWithLabelPlacement
 */
class FieldWithLabelPlacement extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithLabelPlacementSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'label_placement_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'descriptionPlacement' => [
				'type'        => FormFieldDescriptionPlacementEnum::$type,
				'description' => __( 'The placement of the field description.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					return ! empty( $source->descriptionPlacement ) ? $source->descriptionPlacement : 'inherit';
				},
			],
			'labelPlacement'       => [
				'type'        => FormFieldLabelPlacementEnum::$type,
				'description' => __( 'The field label position.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					return ! empty( $source->labelPlacement ) ? $source->labelPlacement : 'inherit';
				},
			],
		];
	}
}
