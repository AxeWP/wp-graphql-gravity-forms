<?php
/**
 * Abstract GraphQL Connection
 *
 * @package WPGraphQL\GF\Connection;
 * @since 0.7.0
 */

namespace WPGraphQL\GF\Connection;

use WPGraphQL\GF\Interfaces\Registrable;

/**
 * Class - AbstractConnection
 */
abstract class AbstractConnection implements Registrable {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name;

	/**
	 * Gets the filterable $config array for the GraphQL connection.
	 *
	 * @param array $config The individual config values.
	 */
	protected static function prepare_config( array $config ) : array {
		/**
		 * Filter for modifying the GraphQL connection $config array used to register the connection in WPGraphQL.
		 *
		 * @param array  $config The config array.
		 * @param string $type The GraphQL type name.
		 */
		$config = apply_filters( 'wp_graphql_gf_connection_config', $config, $config['fromType'], $config['toType'] );

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
	public static function get_filtered_connection_args( array $filter_by = null ) : array {
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
