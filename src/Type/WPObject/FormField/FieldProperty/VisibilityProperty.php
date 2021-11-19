<?php
/**
 * Allows visiibility field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;
use WPGraphQL\GF\Type\Enum\VisibilityPropertyEnum;

/**
 * Class - VisibilityProperty
 */
class VisibilityProperty implements FieldProperty {
	/**
	 * Get 'visibility' property.
	 *
	 * Applies to: @TODO
	 *
	 * @return array
	 */
	public static function get() : array {
		return [
			'visibility' => [
				'type'        => VisibilityPropertyEnum::$type,
				'description' => __( 'Field visibility.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
