<?php
/**
 * Default value field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - DefaultValueProperty
 */
class DefaultValueProperty implements FieldProperty {
	/**
	 * Get 'defaultValue' property.
	 *
	 * Applies to: hidden, text, website, phone, number, date, textarea, email,
	 * post_title, post_content, post_excerpt, post_tags, post_custom_field
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'defaultValue' => [
				'type'        => 'String',
				'description' => __( 'Contains the default value for the field. When specified, the field\'s value will be populated with the contents of this property when the form is displayed.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
