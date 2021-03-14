<?php
/**
 * Enable Enhanced UI field property.
 *
 * @see https://docs.gravityforms.com/field-object/#other
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

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
