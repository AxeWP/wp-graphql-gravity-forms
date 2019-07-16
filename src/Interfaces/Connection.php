<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for classes that register GraphQL Connections.
 */
interface Connection {
	/**
	 * Register connection.
	 */
	public function register_connection();
}
