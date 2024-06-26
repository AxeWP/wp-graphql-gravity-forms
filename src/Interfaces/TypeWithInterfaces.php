<?php
/**
 * Interface for a GraphQL Type with inherited interfaces.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithInterfaces.
 */
interface TypeWithInterfaces {
	/**
	 * Gets the the connection config for the GraphQL Type.
	 *
	 * @return string[]
	 */
	public static function get_interfaces(): array;
}
