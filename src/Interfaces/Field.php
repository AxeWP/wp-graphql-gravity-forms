<?php
/**
 * Interface for a GraphQL Field.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Field
 */
interface Field {
	/**
	 * Register field in GraphQL schema.
	 */
	public static function register_field(): void;
}
