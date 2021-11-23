<?php
/**
 * Allows id subproperty on input property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - InputIdProperty
 */
class InputIdProperty implements FieldProperty {
	/**
	 * Get 'id' property for Input.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'id' => [
				'type'        => 'Float',
				'description' => __( 'The input ID. Input IDs follow the following naming convention: FIELDID.INPUTID (i.e. 5.1), where FIELDID is the id of the containing field and INPUTID specifies the input field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
