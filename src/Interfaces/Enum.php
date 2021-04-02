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
	 */
	public function register() : void;

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string;

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array;

}
