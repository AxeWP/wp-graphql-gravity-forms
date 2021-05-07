<?php
/**
 * Adds enableAutocomplete field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.6.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - EnableAutocompleteProperty
 */
class EnableAutocompleteProperty implements FieldProperty {
	/**
	 * Get 'enableAutocomplete' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'enableAutocomplete' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether autocomplete should be enabled for this field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
