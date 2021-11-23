<?php
/**
 * Coices field property.
 *
 * @see https://docs.gravityforms.com/field-object/#basic-properties
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - ChoicesProperty
 */
class ChoicesProperty implements FieldProperty {
	/**
	 * Get 'choices' property.
	 *
	 * Applies to: select, checkbox, radio, post_category
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'choices' => [
				'type'        => [ 'list_of' => ChoiceProperty::$type ],
				'description' => __( 'Contains the available choices for the field. For instance, drop down items and checkbox items are configured with this property.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
