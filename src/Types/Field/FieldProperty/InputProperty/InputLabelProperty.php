<?php
/**
 * Allows label subproperty on input property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - InputLabelProperty
 */
class InputLabelProperty implements FieldProperty {
	/**
	 * Get 'label' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'label' => [
				'type'        => 'String',
				'description' => __( 'Input label.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
