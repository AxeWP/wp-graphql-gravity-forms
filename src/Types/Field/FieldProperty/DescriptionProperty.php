<?php
/**
 * Description field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - DescriptionProperty
 */
class DescriptionProperty implements FieldProperty {
	/**
	 * Get 'description' property.
	 */
	public static function get() : array {
		return [
			'description' => [
				'type'        => 'String',
				'description' => __( 'Field description.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
