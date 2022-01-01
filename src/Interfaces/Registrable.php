<?php
/**
 * Interface for classes containing WordPress action/filter hooks.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Interfaces;

use WPGraphQL\Registry\TypeRegistry;

/**
 * Interface - registrable
 */
interface Registrable {
	/**
	 * Register connections to the GraphQL Schema.
	 *
	 * @param TypeRegistry $type_registry The GraphQL type registry.
	 */
	public static function register( TypeRegistry $type_registry = null ) : void;
}
