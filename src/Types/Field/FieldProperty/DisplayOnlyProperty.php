<?php
/**
 * Allows display only field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - DisplayOnlyProperty
 */
class DisplayOnlyProperty implements FieldProperty {
	/**
	 * Get 'displayOnly' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'displayOnly' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the field is only displayed and its contents are not submitted with the form/saved with the entry. This is set to true.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
