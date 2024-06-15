<?php
/**
 * Interface for a GraphQL Type with connections.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - TypeWithConnections.
 */
interface TypeWithConnections {
	/**
	 * Gets the properties for the type.
	 *
	 * @return array<string,array{toType:string,description:string,args?:array<string,array{type:string|array<string,string|array<string,string>>,description:string,defaultValue?:mixed}>,connectionInterfaces?:string[],oneToOne?:bool,resolve?:callable}>
	 */
	public static function get_connections(): array;
}
