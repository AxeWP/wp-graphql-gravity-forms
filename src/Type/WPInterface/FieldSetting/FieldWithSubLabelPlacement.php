<?php
/**
 * GraphQL Interface for a FormField with the `sub_label_placement_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\FormFieldSubLabelPlacementEnum;

/**
 * Class - FieldWithSubLabelPlacement
 */
class FieldWithSubLabelPlacement extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithSubLabelPlacementSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'sub_label_placement_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'subLabelPlacement' => [
				'type'        => FormFieldSubLabelPlacementEnum::$type,
				'description' => __( 'The placement of the labels for the subfields within the group. This setting controls all of the subfields, they cannot be set individually. They may be aligned above or below the inputs. If this property is not set, the “Sub-Label Placement” setting on the Form Settings->Form Layout page is used. If no setting is specified, the default is above inputs.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					return ! empty( $source->subLabelPlacement ) ? $source->subLabelPlacement : 'inherit';
				},
			],
		];
	}
}
