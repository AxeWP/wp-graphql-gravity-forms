<?php
/**
 * Interface for a GraphQL TypeWithFields.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithFields.
 */
interface TypeWithFields {
	/**
	 * Gets the fields for the type.
	 *
	 * @return array<string,array{type:string|array<string,string|array<string,string>>,description:string,args?:array<string,array{type:string|array<string,string|array<string,string>>,description:string,defaultValue?:mixed}>,resolve?:callable,deprecationReason?:string}>
	 */
	public static function get_fields(): array;
}
