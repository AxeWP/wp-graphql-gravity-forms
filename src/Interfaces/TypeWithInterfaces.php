<?php
/**
 * Interface for a GraphQL Type with inherited interfaces.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithInterfaces.
 */
interface TypeWithInterfaces {
	/**
	 * Gets the the connection config for the GraphQL Type.
	 */
	public static function get_interfaces(): array;
}
