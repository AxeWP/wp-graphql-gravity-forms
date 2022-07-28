<?php
/**
 * Interface for a GraphQL Type.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Type.
 */
interface TypeWithConnections {
	/**
	 * Gets the the connection config for the GraphQL Type.
	 */
	public static function get_connections() : array;
}
