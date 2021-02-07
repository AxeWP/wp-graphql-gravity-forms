<?php
/**
 * Description placement field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - DescriptionPlacementProperty
 */
abstract class DescriptionPlacementProperty implements FieldProperty {
	/**
	 * Get Description placement property.
	 * This is different from the 'descriptionPlacement' Form field.
	 *
	 * Applies to: list, multiselect, password
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			// @TODO - Convert to enum. Possible values: "above" or "below"
			'choices' => [
				'type'        => 'String',
				'description' => __( 'The placement of the field description. The description may be placed “above” or “below” the field inputs. If the placement is not specified, then the description placement setting for the Form Layout is used.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
