<?php
/**
 * Input name field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - InputNameProperty
 */
class InputNameProperty implements FieldProperty {
	/**
	 * Get 'inputName' property.
	 *
	 * Applies to: All fields except section and captcha
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'inputName' => [
				'type'        => 'String',
				'description' => __( 'Assigns a name to this field so that it can be populated dynamically via this input name. Only applicable when allowsPrepopulate is set to 1.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
