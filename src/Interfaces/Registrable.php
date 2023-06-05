<?php
/**
 * Interface for classes containing WordPress action/filter hooks.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - registrable
 */
interface Registrable {
	/**
	 * Register connections to the GraphQL Schema.
	 */
	public static function register(): void;
}
