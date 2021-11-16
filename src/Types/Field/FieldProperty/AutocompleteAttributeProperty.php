<?php
/**
 * Autocomplete attribute field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.6.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

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
