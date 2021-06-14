<?php
/**
 * Interface for a GraphQL Type.
 *
 * @package WPGraphQLGravityForms\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Interfaces;

/**
 * Interface - Type.
 */
interface Type {
	/**
	 * Register type in GraphQL schema.
	 */
	// TODO: Determine best way to re-implement this
	// now that Types\Union\ObjectFieldUnion::register_type()
	// requires an argument.
	// public function register_type(); .

	/**
	 * Gets the Field type description.
	 *
	 * @return string
	 */
	public function get_type_description() : string;

	/**
	 * Gets the filterable $config array for the GraphQL type.
	 *
	 * @param array $config The individual config values.
	 *
	 * @return array
	 */
	public function get_type_config( array $config ) : array;
}
