<?php
/**
 * Interface for a GraphQL Enum.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Enum.
 */
// phpcs:ignore PHPCompatibility.Keywords.ForbiddenNames.enumFound -- @todo Remove b/c
interface Enum {
	/**
	 * Gets the Enum type values.
	 */
	public static function get_values(): array;
}
