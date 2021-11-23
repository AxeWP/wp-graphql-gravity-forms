<?php
/**
 * Input name field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

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
