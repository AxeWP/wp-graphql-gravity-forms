<?php
/**
 * Interface for classes containing WordPress action/filter hooks.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - registrable
 */
interface Hookable {
	/**
	 * Hooks class into WordPress.
	 */
	public static function register_hooks(): void;
}
