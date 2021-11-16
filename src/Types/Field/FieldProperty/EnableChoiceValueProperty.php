<?php
/**
 * Enable ChoiceValue field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - DefaultValueProperty
 */
class EnableChoiceValueProperty implements FieldProperty {
	/**
	 * Get 'enableChoiceValue' property.
	 *
	 * Applies to: checkbox, select and radio
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'enableChoiceValue' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field (checkbox, select or radio) have choice values enabled, which allows the field to have choice values different from the labels that are displayed to the user.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
