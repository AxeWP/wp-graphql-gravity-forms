<?php

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface for classes containing WordPress action/filter hooks.
 */
interface Hookable {
	/**
	 * Register hooks with WordPress.
	 */
	public function register_hooks();
}
