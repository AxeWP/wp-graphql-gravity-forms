<?php
/**
 * Placeholder field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - PlaceholderProperty
 */
class PlaceholderProperty implements FieldProperty {
	/**
	 * Get 'placeholder' property.
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
