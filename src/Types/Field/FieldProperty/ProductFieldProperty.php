<?php
/**
 * Allows product field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - ProductFieldProperty
 */
class ProductFieldProperty implements FieldProperty {
	/**
	 * Get 'productField' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'productField' => [
				'type'        => 'Int',
				'description' => __( 'The id of the product field to which the field is associated.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
