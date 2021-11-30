<?php
/**
 * Label Placement field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;
use WPGraphQL\GF\Type\Enum\LabelPlacementPropertyEnum;

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
				'resolve'     => function( $source ) {
					return ! empty( $source['labelPlacement'] ) ? $source['labelPlacement'] : 'inherit';
				},
			],
		];
	}
}
