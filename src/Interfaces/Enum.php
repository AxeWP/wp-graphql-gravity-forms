<?php
/**
 * Interface for a GraphQL Enum.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Enum.
 */
interface Enum {
	/**
	 * Gets the Enum type values.
	 */
	public static function get_values() : array;
}
