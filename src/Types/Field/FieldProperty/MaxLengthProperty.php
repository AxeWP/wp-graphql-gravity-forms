<?php
/**
 * Max length field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use GF_Field;
use WPGraphQL\GF\Interfaces\FieldProperty;

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
				'type'        => 'Int',
				'description' => __( 'Specifies the maximum number of characters allowed in a text or textarea (paragraph) field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( GF_Field $field ) : int {
					return (int) $field['maxLength'];
				},
			],
		];
	}
}
