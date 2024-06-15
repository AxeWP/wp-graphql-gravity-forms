<?php
/**
 * Interface for a GraphQL Mutation.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Mutation.
 */
interface Mutation {
	/**
	 * Gets the input fields for the mutation.
	 *
	 * @return array<string,array{type:string|array<string,string|array<string,string>>,description:string,defaultValue?:string}>
	 */
	public static function get_input_fields(): array;

	/**
	 * Gets the fields for the type.
	 *
	 * @return array<string,array{type:string|array<string,string|array<string,string>>,description:string,args?:array<string,array{type:string|array<string,string|array<string,string>>,description:string,defaultValue?:mixed}>,resolve?:callable,deprecationReason?:string}>
	 */
	public static function get_output_fields(): array;

	/**
	 * Defines the data modification closure.
	 *
	 * @since 0.4.0
	 */
	public static function mutate_and_get_payload(): callable;
}
