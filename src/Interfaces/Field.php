<?php
/**
 * Interface for a GraphQL Field.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface - Field
 */
interface Field {
	/**
	 * Register field in GraphQL schema.
	 */
	public function register_field() : void;
}
