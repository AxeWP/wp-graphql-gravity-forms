<?php
/**
 * Interface for a GraphQL Type with connections.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithConnections.
 */
interface TypeWithConnections {
	/**
	 * Gets the the connection config for the GraphQL Type.
	 */
	public static function get_connections(): array;
}
