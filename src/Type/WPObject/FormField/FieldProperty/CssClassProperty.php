<?php
/**
 * Autocomplete attribute field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.6.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - CssClass
 */
class CssClassProperty implements FieldProperty {
	/**
	 * Get 'AutocompleteAttribute' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'cssClass' => [
				'type'        => 'String',
				'description' => __( 'String containing the custom CSS classes to be added to the <li> tag that contains the field. Useful for applying custom formatting to specific fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
