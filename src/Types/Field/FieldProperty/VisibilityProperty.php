<?php
/**
 * Allows visiibility field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\FieldProperty;
use WPGraphQLGravityForms\Types\Enum\VisibilityPropertyEnum;

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
