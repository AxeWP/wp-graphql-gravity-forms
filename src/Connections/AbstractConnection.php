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
		add_action( 'init', [ $this, 'register_connection' ] );
	}

	/**
	 * Register connection from GravityFormsEntry type to other types.
	 */
	public function register_connection() : void {
		register_graphql_connection(
			$this->prepare_connection_config(
				[
					'fromType'      => $this->get_connection_from_type(),
					'toType'        => $this->get_connection_to_type(),
					'fromFieldName' => static::$from_field_name,
				]
			)
		);
	}

	/**
	 * GraphQL Connection from type.
	 *
	 * @return string
	 */
	abstract public function get_connection_from_type() : string;

	/**
	 * GraphQL Connection to type.
	 */
	abstract public function get_connection_to_type() : string;

	/**
	 * Gets the filterable $config array for the GraphQL connection.
	 *
	 * @param array $config The individual config values.
	 */
	private function prepare_connection_config( array $config ) : array {
		$config = array_merge( $config, $this->get_connection_config_args() );

		/**
		 * Filter for modifying the GraphQL connection $config array used to register the connection in WPGraphQL.
		 *
		 * @param array  $config The config array.
		 * @param string $type The GraphQL type name.
		 */
		$config = apply_filters( 'wp_graphql_gf_connection_config', $config, $this->get_connection_from_type(), $this->get_connection_to_type() );

		return $config;
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array
	 */
	abstract public function get_connection_config_args() : array;
}
