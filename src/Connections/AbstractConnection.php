<?php
/**
 * Abstract GraphQL Connection
 *
 * @package WPGraphQLGravityForms\Connections;
 * @since 0.7.0
 */

namespace WPGraphQLGravityForms\Connections;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Class - AbstractConnection
 */
abstract class AbstractConnection implements Hookable {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name;

	/**
	 * {@inheritDoc}.
	 */
	public function register_hooks() : void {
		add_action( 'init', [ $this, 'register_connections' ] );
	}

	/**
	 * Register connection from GravityFormsEntry type to other types.
	 */
	abstract public function register_connections() : void;

	/**
	 * Gets the filterable $config array for the GraphQL connection.
	 *
	 * @param array $config The individual config values.
	 */
	protected function prepare_connection_config( array $config ) : array {
		//phpcs:disable
		// Deprecate types from filter arguments.
		// add_filter( 'wp_graphql_gf_connection_config', [ $this, 'deprecate_filter_args' ], 10, 3 );
		//phpcs:enable

		/**
		 * Filter for modifying the GraphQL connection $config array used to register the connection in WPGraphQL.
		 *
		 * @param array  $config The config array.
		 * @param string $type The GraphQL type name.
		 */
		$config = apply_filters( 'wp_graphql_gf_connection_config', $config, $config['fromType'], $config['toType'] );

		//phpcs:disable
		// remove_filter( 'wp_graphql_gf_connection_config', [ $this, 'deprecate_filter_args' ] );
		//phpcs:enable

		return $config;
	}

	/**
	 * Deprecates `$from_type` and $to_type` from `wp_graphql_gf_connection_config` filter.
	 *
	 * @param array       $config .
	 * @param string|null $from_type Deprecated.
	 * @param string|null $to_type Deprecated.
	 *
	 * @return array
	 */
	public function deprecate_filter_args( array $config, $from_type = null, $to_type = null ) : array {
		if ( ! empty( $from_type ) || ! empty( $to_type ) ) {
			_deprecated_argument( 'wp_graphql_gf_connection_config', '0.8.0', esc_attr__( '`$from_type` and `$to_type` arguments have been deprecated. Please use `$config[\'fromType\']` and `$config[\'toType\'] instead.', 'wp-graphql-gravity-forms' ) );
		}
		return $config;
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array
	 */
	public static function get_connection_args() : array {
		return [];
	}

	/**
	 * Returns a filtered array of connection args.
	 *
	 * @param array $filter_by .
	 */
	protected static function get_filtered_connection_args( array $filter_by = null ) : array {
		$connection_args = static::get_connection_args();

		if ( empty( $filter_by ) ) {
			return $connection_args;
		}

		$filtered_args = [];
		foreach ( $filter_by as $filter ) {
			$filtered_args[ $filter ] = $connection_args[ $filter ];
		}

		return $filtered_args;
	}
}
