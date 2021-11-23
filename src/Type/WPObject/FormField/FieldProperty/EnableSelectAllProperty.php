<?php
/**
 * Allows enable select all field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - EnableSelectAllProperty
 */
class EnableSelectAllProperty implements FieldProperty {
	/**
	 * Get 'enableSelectAll' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'enableSelectAll' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the "select all" choice should be displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
