<?php
/**
 * Size field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - SizeProperty
 */
abstract class SizeProperty implements FieldProperty {
	/**
	 * Get 'size' property.
	 *
	 * Applies to: All fields except html, section and captcha
	 * Possible values: small, medium, large
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			// @TODO: Convert to enum. Possible values: small, medium, large
			'size' => [
				'type'        => 'String',
				'description' => __( 'Determines the size of the field when displayed on the page. Possible values are: "small", "medium", "large".', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
