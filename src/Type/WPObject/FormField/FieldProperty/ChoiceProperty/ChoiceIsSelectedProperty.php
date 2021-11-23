<?php
/**
 * Allows isSelected subproperty on Choice property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ChoiceProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ChoiceProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - ChoiceIsSelectedProperty
 */
class ChoiceIsSelectedProperty implements FieldProperty {
	/**
	 * Get 'isSelected' property for Choice.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'isSelected' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if this choice should be selected by default when displayed. The value true will select the choice, whereas false will display it unselected.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
