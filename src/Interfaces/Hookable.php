<?php
/**
 * Interface for classes containing WordPress action/filter hooks.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Hookable
 */
interface Hookable {
	/**
	 * Register hooks with WordPress.
	 */
	public function register_hooks() : void;
}
