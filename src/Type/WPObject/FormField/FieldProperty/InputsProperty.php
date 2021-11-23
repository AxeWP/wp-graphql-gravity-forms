<?php
/**
 * Inputs field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - InputsProperty
 */
class InputsProperty implements FieldProperty {
	/**
	 * Get 'inputs' property.
	 *
	 * Applies to: name, address
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'inputs' => [
				'type'        => [ 'list_of' => InputProperty::$type ],
				'description' => __( 'An array containing the the individual properties for each element of the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
