<?php
/**
 * Allows visiibility field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.1.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - VisibilityProperty
 */
abstract class VisibilityProperty implements FieldProperty {
	/**
	 * Get 'visibility' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'visibility' => [
				'type'        => 'String',
				'description' => __( 'Field visibility. Possible values: visible, hidden, or administrative.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
