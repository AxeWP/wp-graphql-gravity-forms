<?php
/**
 * Allows placeholder subproperty on input property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - InputPlaceholderProperty
 */
class InputPlaceholderProperty implements FieldProperty {
	/**
	 * Get 'placeholder' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'placeholder' => [
				'type'        => 'String',
				'description' => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
