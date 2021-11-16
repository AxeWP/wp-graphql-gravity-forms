<?php
/**
 * Allows text subproperty on Choice property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty\ChoiceProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty\ChoiceProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - ChoiceTextProperty
 */
class ChoiceTextProperty implements FieldProperty {
	/**
	 * Get 'text' property for Choice.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'text' => [
				'type'        => 'String',
				'description' => __( 'The text to be displayed to the user when displaying this choice.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
