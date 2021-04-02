<?php
/**
 * Interface for a GraphQL Input Type.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface - InputType
 */
interface InputType {
	/**
	 * Register input type in GraphQL schema.
	 */
	public function register_input_type() : void;
}
