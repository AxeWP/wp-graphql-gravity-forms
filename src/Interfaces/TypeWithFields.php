<?php
/**
 * Interface for a GraphQL TypeWithFields.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithFields.
 */
interface TypeWithFields {
	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array<string,array<string,mixed>> The GraphQL field configs for the type.
	 */
	public static function get_fields(): array;
}
