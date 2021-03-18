<?php
/**
 * Allows defaultValue subproperty on input property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - InputDefaultValueProperty
 */
class InputDefaultValueProperty implements FieldProperty {
	/**
	 * Get 'defaultValue' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'defaultValue' => [
				'type'        => 'String',
				'description' => __( 'The default value to be displayed/chosen in the input field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
