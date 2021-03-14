<?php
/**
 * Allows prepopulate field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - AllowsPrepopulateProperty
 */
class AllowsPrepopulateProperty implements FieldProperty {
	/**
	 * Get 'allowsPrepopulate' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'allowsPrepopulate' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field’s value can be pre-populated dynamically. 1 to allow field to be pre-populated, 0 otherwise.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
