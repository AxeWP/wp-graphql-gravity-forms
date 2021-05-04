<?php
/**
 * Admin label field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - AutocompleteAttributeProperty
 */
class AutocompleteAttributeProperty implements FieldProperty {
	/**
	 * Get 'AutocompleteAttribute' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'autocompleteAttribute' => [
				'type'        => 'String',
				'description' => __( 'The autocomplete attribute for the field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
