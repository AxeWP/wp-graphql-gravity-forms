<?php
/**
 * Interface for a GraphQL Enum.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Enum.
 */
// phpcs:ignore PHPCompatibility.Keywords.ForbiddenNames.enumFound -- @todo Remove b/c
interface Enum {
	/**
	 * Gets the Enum values configuration array.
	 *
	 * @return array<string,array{description:string,value:mixed,deprecationReason?:string}>
	 */
	public static function get_values(): array;
}
