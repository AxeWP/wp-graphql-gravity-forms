<?php
/**
 * Enable Enhanced UI field property.
 *
 * @see https://docs.gravityforms.com/field-object/#other
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - EnableEnhancedUiProperty
 */
class EnableEnhancedUiProperty implements FieldProperty {
	/**
	 * Get 'enableEnhancedUI' property.
	 *
	 * Applies to: select, multiselect
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'enableEnhancedUI' => [
				'type'        => 'Boolean',
				'description' => __( 'When set to true, the "Chosen" jQuery script will be applied to this field, enabling search capabilities to Drop Down fields and a more user-friendly interface for Multi Select fields.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
