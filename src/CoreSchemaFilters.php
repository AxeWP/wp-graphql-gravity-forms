<?php
/**
 * Adds filters that modify core schema.
 *
 * @package WPGraphQL\GF
 * @since   0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF;

use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Interfaces\Hookable;

/**
 * Class - CoreSchemaFilters
 */
class CoreSchemaFilters implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		// Resolve node types to models.
		add_filter( 'graphql_resolve_node_type', [ Factory::class, 'resolve_node_type' ], 10, 2 );

		// Change max query amount for form fields.
		add_filter( 'graphql_connection_max_query_amount', [ Factory::class, 'set_max_query_amount' ], 11, 5 );

		// Register data loaders.
		add_filter( 'graphql_data_loader_classes', [ Factory::class, 'register_loader_classes' ], 10 );
	}
}
