<?php
/**
 * Is required field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - IsRequiredProperty
 */
class IsRequiredProperty implements FieldProperty {
	/**
	 * Get 'isRequired' property.
	 *
	 * Applies to: All fields except section, html and captcha
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'isRequired' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field requires the user to enter a value. 1 marks the field as required, 0 marks the field as not required. Fields marked as required will prevent the form from being submitted if the user has not entered a value in it.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
