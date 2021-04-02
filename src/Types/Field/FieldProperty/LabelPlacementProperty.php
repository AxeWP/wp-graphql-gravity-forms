<?php
/**
 * Label Placement field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;
use WPGraphQLGravityForms\Types\Enum\LabelPlacementPropertyEnum;

/**
 * Class - LabelPlacementProperty
 */
class LabelPlacementProperty implements FieldProperty {
	/**
	 * Get 'labelPlacement' property.
	 *
	 * Applies to: address, list
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'labelPlacement' => [
				'type'        => LabelPlacementPropertyEnum::$type,
				'description' => __( 'The field label position.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
