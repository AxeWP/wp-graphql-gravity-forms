<?php
/**
 * Interface for a GraphQL TypeWithInputFields.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.13.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithInputFields.
 */
interface TypeWithInputFields {
	/**
	 * Gets the input fields for the type.
	 *
	 * @return array<string,array{type:string|array<string,string|array<string,string>>,description:callable():string,defaultValue?:string}>
	 */
	public static function get_fields(): array;
}
