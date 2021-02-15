<?php
/**
 * Interface for Gravity Forms field properties.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

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
