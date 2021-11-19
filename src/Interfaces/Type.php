<?php
/**
 * Interface for a GraphQL Type.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Type.
 */
interface Type {
	/**
	 * Register type in GraphQL schema.
	 */
	// TODO: Determine best way to re-implement this
	// now that Types\Union\ObjectFieldUnion::register()
	// requires an argument.
	// public function register(); .

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
	public function get_config( array $config ) : array;
}
