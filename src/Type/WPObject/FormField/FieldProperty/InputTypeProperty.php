<?php
/**
 * Allows input type field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - InputTypeProperty
 */
class InputTypeProperty implements FieldProperty {
	/**
	 * Get 'inputType' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'inputType' => [
				'type'        => 'String',
				'description' => __( 'Contains a field type and allows a field type to be displayed as another field type. A good example is the Post Custom Field, that can be displayed as various different types of fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
