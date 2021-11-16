<?php
/**
 * Allows visiibility field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Interfaces\FieldProperty;
use WPGraphQL\GF\Types\Enum\VisibilityPropertyEnum;

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
