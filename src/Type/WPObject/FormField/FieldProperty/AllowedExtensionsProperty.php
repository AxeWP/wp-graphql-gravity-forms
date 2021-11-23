<?php
/**
 * Allowed Extensions field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.6.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;

/**
 * Class - AllowedExtensionsProperty
 */
class AllowedExtensionsProperty implements FieldProperty {
	/**
	 * Get 'AllowedExtensions' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'allowedExtensions' => [
				'type'        => 'String',
				'description' => __( 'A comma-delimited list of the file extensions which may be uploaded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
