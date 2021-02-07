<?php
/**
 * Interface for a GraphQL Mutation.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface - Mutation.
 */
interface Mutation {
	/**
	 * Register mutation in GraphQL schema.
	 */
	public function register_mutation();
}
