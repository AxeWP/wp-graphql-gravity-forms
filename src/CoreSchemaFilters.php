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

		if ( defined( 'WPGRAPHQL_VERSION' ) && version_compare( WPGRAPHQL_VERSION, '2.3.2', '>=' ) ) {
			// Register data loaders classes.
			add_filter( 'graphql_data_loader_classes', [ Factory::class, 'register_loader_classes' ], 10 );
		} else {
			// @todo remove once WPGraphQL 2.3.2+ is required.
			add_filter(
				'graphql_data_loaders',
				static function ( $loaders, $context ) {
					// We just get the class names.
					$loader_classes = Factory::register_loader_classes( [] );
					foreach ( $loader_classes as $name => $class ) {
						$loaders[ $name ] = new $class( $context );
					}
					return $loaders;
				},
				10,
				2
			);
		}
	}
}
