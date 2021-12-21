<?php
/**
 * Adds filters that modify core schema.
 *
 * @package WPGraphQL\GF
 * @since   0.10.0
 */

namespace WPGraphQL\GF;

use WPGraphQL\GF\Data\Factory;

/**
 * Class - CoreSchemaFilters
 */
class CoreSchemaFilters {
	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks() : void {
		// Register Data Loaders.
		add_filter( 'graphql_data_loaders', [ Factory::class, 'register_loaders' ], 10, 2 );

		// Resolve node types to models.
		add_filter( 'graphql_resolve_node_type', [ Factory::class, 'resolve_node_type' ], 10, 2 );

		// // Change max query amount for form fields.
		add_filter( 'graphql_connection_max_query_amount', [ Factory::class, 'set_max_query_amount' ], 11, 5 );
	}
}
