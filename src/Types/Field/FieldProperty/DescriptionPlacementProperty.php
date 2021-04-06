<?php
/**
 * Description placement field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;
use WPGraphQLGravityForms\Types\Enum\DescriptionPlacementPropertyEnum;

/**
 * Class - DescriptionPlacementProperty
 */
class DescriptionPlacementProperty implements FieldProperty {
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
			'descriptionPlacement' => [
				'type'        => DescriptionPlacementPropertyEnum::$type,
				'description' => __( 'The placement of the field description.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
