<?php
/**
 * Allows sublabel placement field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - SubLabelPlacementProperty
 */
class SubLabelPlacementProperty implements FieldProperty {
	/**
	 * Get 'subLabelPlacement' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'subLabelPlacement' => [
				'type'        => 'String',
				'description' => __( 'The placement of the labels for the subfields within the group. This setting controls all of the subfields, they cannot be set individually. They may be aligned above or below the inputs. If this property is not set, the “Sub-Label Placement” setting on the Form Settings->Form Layout page is used. If no setting is specified, the default is above inputs.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
