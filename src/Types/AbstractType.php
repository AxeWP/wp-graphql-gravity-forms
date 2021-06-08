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
	public function register_type() : void {
		register_graphql_object_type( static::$type, $this->get_type_config() );
	}

	/**
	 * Gets the Field type description.
	 *
	 * @return string
	 */
	abstract protected function get_type_description() : string;

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	abstract protected function get_type_fields() : array;

	/**
	 * Gets the filterable $config array for the GraphQL type.
	 *
	 * @return array
	 */
	protected function get_type_config() : array {
		$description = $this->get_type_description();

		$description = apply_filters( 'wp_graphql_gf_type_description', $description, static::$type );
		$description = apply_filters( 'wp_graphql_gf_' . static::$type . '_type_description', $description );

		$fields = $this->get_type_fields();

		$fields = apply_filters( 'wp_graphql_gf_type_properties', $fields, static::$type );
		$fields = apply_filters( 'wp_graphql_gf_' . static::$type . '_type_properties', $fields );

		$config = [
			'description' => $description,
			'fields'      => $fields,
		];

		$config = apply_filters( 'wp_graphql_gf_type_config', $config, static::$type );

		$config = apply_filters( 'wp_graphql_gf_' . static::$type . '_type_config', $config );

		return $config;
	}
}
