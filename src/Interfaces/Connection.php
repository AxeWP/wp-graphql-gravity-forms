<?php
/**
 * Interface for classes that register GraphQL Connections.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface - Connection
 */
interface Connection {
	/**
	 * Register connection.
	 */
	public function register_connection() : void;
}
