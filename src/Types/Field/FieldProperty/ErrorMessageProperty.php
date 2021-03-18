<?php
/**
 * Error message field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - ErrorMessageProperty
 */
class ErrorMessageProperty implements FieldProperty {
	/**
	 * Get 'errorMessage' property.
	 *
	 * Applies to: All fields except html, section and hidden
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'errorMessage' => [
				'type'        => 'String',
				'description' => __( 'Contains the message that is displayed for fields that fail validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
