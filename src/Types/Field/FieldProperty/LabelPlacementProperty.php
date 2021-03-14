<?php
/**
 * Label Placement field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - LabelPlacementProperty
 */
class LabelPlacementProperty implements FieldProperty {
	/**
	 * Get 'labelPlacement' property.
	 *
	 * Applies to: address, list
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			// @TODO - Convert to enum. See corresponding Form 'labelPlacement' field.
			'labelPlacement' => [
				'type'        => 'String',
				'description' => __( 'The field label position. Empty when using the form defaults or a value of "hidden_label".', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
