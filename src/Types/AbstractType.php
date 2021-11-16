<?php
/**
 * Abstract GraphQL Type.
 *
 * @package WPGraphQL\GF\Types
 * @since 0.7.0
 */

namespace WPGraphQL\GF\Types;

use WPGraphQL\GF\Interfaces\Hookable;
use WPGraphQL\GF\Interfaces\Type;

/**
 * Class - AbstractType
 */
abstract class AbstractType implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type;

	/**
	 * Whether the type should be loaded eagerly by WPGraphQL. Defaults to false.
	 *
	 * Eager load should only be necessary for types that are not referenced directly (e.g. in Unions, Interfaces ).
	 *
	 * @var boolean
	 */
	public static $should_load_eagerly = false;

	/**
	 * {@inheritDoc}
	 */
	public function register_hooks() : void {
		add_action( get_graphql_register_action(), [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	abstract public function register_type() : void;

	/**
	 * Gets the filterable $config array for the GraphQL type.
	 *
	 * @param array $config The individual config values.
	 *
	 * @return array
	 */
	public function get_type_config( array $config ) : array {
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
	 * Filters and sorts the fields before register().
	 */
	protected function prepare_fields() : array {
		/**
		 * Call deprecated get_properties() function, in case it's used in a child class.
		 *
		 * @since 0.7.0
		 */
		$fields = $this->get_type_fields();
		if ( method_exists( $this, 'get_properties' ) ) {
			_deprecated_function( 'get_properties', '0.7.0', 'get_type_fields' );
			$fields = array_merge( $fields, $this->get_properties() );
		}

		/**
		 * Sort the fields alpahbetically by key.
		 */
		ksort( $fields );

		return $fields;
	}

	/**
	 * Gets the properties for the Field. Not abstract, so deprecated child classes don't break.
	 *
	 * @todo convert to abstract class.
	 */
	public function get_type_fields() : array {
		return [];
	}
}
