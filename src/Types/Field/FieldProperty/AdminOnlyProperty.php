<?php
/**
 * Admin only field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - AdminOnlyProperty
 */
class AdminOnlyProperty implements FieldProperty {
	/**
	 * Get 'adminOnly' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'adminOnly' => [
				'type'        => 'Boolean',
				'description' => __( 'Determines if this field should only visible on the administration pages. A value of 1 will mark the field as admin only and will hide it from the public form. Useful for fields such as “status” that help with managing entries, but don’t apply to users filling out the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
