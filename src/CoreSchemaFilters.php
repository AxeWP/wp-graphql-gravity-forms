<?php
/**
 * Adds filters that modify core schema.
 *
 * @package WPGraphQL\GF
 * @since   0.10.0
 */

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
		// Register Data Loaders.
		add_filter( 'graphql_data_loaders', [ Factory::class, 'register_loaders' ], 10, 2 );

		// Resolve node types to models.
		add_filter( 'graphql_resolve_node_type', [ Factory::class, 'resolve_node_type' ], 10, 2 );

		// Change max query amount for form fields.
		add_filter( 'graphql_connection_max_query_amount', [ Factory::class, 'set_max_query_amount' ], 11, 5 );

		// Strip `Connection` interface from form fields.
		add_filter( 'graphql_type_interfaces', [ self::class, 'strip_connection_interface_from_gf_fields' ], 10, 2 );
	}

	/**
	 * Strip `Connection` interface from form fields.
	 *
	 * @param array $interfaces Array of interfaces.
	 * @param array $config  The type config.
	 *
	 * @return array
	 */
	public static function strip_connection_interface_from_gf_fields( array $interfaces, array $config ): array {
		// Bail early if Connection, 'Edge, or 'OneToOneConnection' arent in the interfaces.
		if ( ! in_array( 'Connection', $interfaces, true ) && ! in_array( 'Edge', $interfaces, true ) && ! in_array( 'OneToOneConnection', $interfaces, true ) ) {
			return $interfaces;
		}

		// If the config name contains `FormFieldConnection`, remove the `Connection` interface.
		if ( false !== strpos( $config['name'], 'FormFieldConnection' ) || false !== strpos( $config['name'], 'QuizFieldConnection' ) ) {
			$interfaces = array_filter(
				$interfaces,
				static function ( $interface ) {
					return ! in_array( $interface, [ 'Connection', 'Edge', 'OneToOneConnection' ], true );
				}
			);
		}

		return $interfaces;
	}
}
