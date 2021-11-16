<?php
/**
 * Allows name subproperty on input property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty\InputProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - InputNameProperty
 */
class InputNameProperty implements FieldProperty {
	/**
	 * Get 'name' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'name' => [
				'type'        => 'String',
				'description' => __( 'When the field is configured with allowsPrepopulate set to 1, this property contains the parameter name to be used to populate this field (equivalent to the inputName property of single-input fields).', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
