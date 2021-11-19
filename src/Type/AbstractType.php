<?php
/**
 * Abstract GraphQL Type.
 *
 * @package WPGraphQL\GF\Type
 * @since 0.7.0
 */

namespace WPGraphQL\GF\Type;

use WPGraphQL\GF\Interfaces\Registrable;
/**
 * Class - AbstractType
 */
abstract class AbstractType implements Registrable {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type;

	/**
	 * Whether the type should be loaded eagerly by WPGraphQL. Defaults to false.
	 *
	 * Eager load should only be necessary for types that are not referenced directly (e.g. in Unions, Interfaces ).
	 *
	 * @var boolean
	 */
	public static bool $should_load_eagerly = false;

	/**
	 * Gets the filterable $config array for the GraphQL type.
	 *
	 * @param array $config The individual config values.
	 *
	 * @return array
	 */
	public static function prepare_config( array $config ) : array {
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


	/**
	 * Get the description for the type.
	 */
	abstract public static function get_description() : string;
}
