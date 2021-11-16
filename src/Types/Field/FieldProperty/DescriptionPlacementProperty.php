<?php
/**
 * Description placement field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;
use WPGraphQL\GF\Types\Enum\DescriptionPlacementPropertyEnum;

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
