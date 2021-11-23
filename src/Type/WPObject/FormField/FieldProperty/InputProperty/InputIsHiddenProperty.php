<?php
/**
 * Allows isHidden subproperty on input property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - InputIsHiddenProperty
 */
class InputIsHiddenProperty implements FieldProperty {
	/**
	 * Get 'isHidden' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'isHidden' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether or not this field should be hidden.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
