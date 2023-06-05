<?php
/**
 * Interface for a GraphQL Type with a description.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithDescription.
 */
interface TypeWithDescription {
	/**
	 * Gets the Field type description.
	 */
	public static function get_description(): string;
}
