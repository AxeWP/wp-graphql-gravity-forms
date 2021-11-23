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
	 * Gets the Field type description.
	 */
	public static function get_description() : string;

	/**
	 * Gets the filterable $config array for the GraphQL type.
	 *
	 * @param array $config The individual config values.
	 */
	public static function prepare_config( array $config ) : array;
}
