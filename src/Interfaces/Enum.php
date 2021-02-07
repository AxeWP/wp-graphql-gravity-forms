<?php
/**
 * Interface for a GraphQL Enum.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface  - Enum
 */
interface Enum {
	/**
	 * Register a WPGraphQL Enum.
	 *
	 * @return void
	 */
	public function register();
}
