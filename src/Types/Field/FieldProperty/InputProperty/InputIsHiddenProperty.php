<?php
/**
 * Allows isHidden subproperty on input property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

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
