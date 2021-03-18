<?php
/**
 * Allows customLabel subproperty on input property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - InputCustomLabelProperty
 */
class InputCustomLabelProperty implements FieldProperty {
	/**
	 * Get 'customLabel' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'customLabel' => [
				'type'        => 'String',
				'description' => __( 'The custom label for the input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
