<?php
/**
 * Max length field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\FieldProperty;

/**
 * Class - MaxLengthProperty
 */
class MaxLengthProperty implements FieldProperty {
	/**
	 * Get 'maxLength' property.
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'maxLength' => [
				'type'        => 'Integer',
				'description' => __( 'Specifies the maximum number of characters allowed in a text or textarea (paragraph) field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( GF_Field $field ) : int {
					return (int) $field['maxLength'];
				},
			],
		];
	}
}
