<?php
/**
 * Allows page number field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.1.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - PageNumberProperty
 */
abstract class PageNumberProperty implements FieldProperty {
	/**
	 * Get 'pageNumber' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'pageNumber' => [
				'type'        => 'Integer',
				'description' => __( 'The form page this field is located on. Default is 1.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
