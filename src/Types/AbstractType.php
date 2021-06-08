<?php
/**
 * Abstract GraphQL Type.
 *
 * @package WPGraphQLGravityForms\Types;
 */

namespace WPGraphQLGravityForms\Types;

use WPGraphQLGravityForms\Interfaces\Hookable;

/**
 * Class - AbstractType
 */
abstract class AbstractType implements Hookable {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type;

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	abstract public function register_type() : void;

	/**
	 * Gets the Field type description.
	 *
	 * @return string
	 */
	abstract protected function get_type_description() : string;


	/**
	 * Gets the filterable $config array for the GraphQL type.
	 *
	 * @param array $config The individual config values.
	 *
	 * @return array
	 */
	protected function get_type_config( array $config ) : array {
		/**
		 * Filter for modifying the GraphQL type $config array used to register the type in WPGraphQL.
		 *
		 * @param array  $config The config array.
		 * @param string $type The GraphQL type name.
		 */
		$config = apply_filters( 'wp_graphql_gf_type_config', $config, static::$type );
		$config = apply_filters( 'wp_graphql_gf_' . static::$type . '_type_config', $config );

		return $config;
	}
}
