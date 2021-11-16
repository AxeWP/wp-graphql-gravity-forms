<?php
/**
 * No duplicates field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - NoDuplicatesProperty
 */
class NoDuplicatesProperty implements FieldProperty {
	/**
	 * Get 'noDuplicates' property.
	 *
	 * Applies to: hidden, text, website, phone, number, date, time, textarea,
	 * select, radio, email, post_custom_field
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'noDuplicates' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if the field allows duplicate submissions.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
