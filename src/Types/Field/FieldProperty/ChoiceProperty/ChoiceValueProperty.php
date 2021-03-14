<?php
/**
 * Allows value subproperty on Choice property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\ChoiceProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ChoiceProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - ChoiceValueProperty
 */
class ChoiceValueProperty implements FieldProperty {
	/**
	 * Get 'value' property for Choice.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The value to be stored in the database when this choice is selected. Note: This property is only supported by the Drop Down and Post Category fields. Checkboxes and Radio fields will store the text property in the database regardless of the value property.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
