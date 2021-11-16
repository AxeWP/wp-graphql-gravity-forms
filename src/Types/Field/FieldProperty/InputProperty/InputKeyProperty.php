<?php
/**
 * Allows key subproperty on input property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty\InputProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - InputKeyProperty
 */
class InputKeyProperty implements FieldProperty {
	/**
	 * Get 'key' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'key' => [
				'type'        => 'String',
				'description' => __( 'Key used to identify this input.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
