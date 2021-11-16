<?php
/**
 * Label Placement field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;
use WPGraphQL\GF\Types\Enum\LabelPlacementPropertyEnum;

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
