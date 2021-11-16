<?php
/**
 * Interface for Gravity Forms field properties.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - FieldProperty
 */
interface FieldProperty {
	/**
	 * Get the field property.
	 *
	 * @return array Field property data.
	 */
	public static function get() : array;
}
