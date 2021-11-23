<?php
/**
 * Allows customLabel subproperty on input property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

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
