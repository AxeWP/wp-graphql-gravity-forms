<?php
/**
 * Interface for a GraphQL Mutation.
 *
 * @package WPGraphQL\GF\Interfaces
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Interfaces;

/**
 * Interface - Mutation.
 */
interface Mutation {

	/**
	 * Register mutation in GraphQL schema.
	 */
	public function register_mutation() : void;

	/**
	 * Defines the input field configuration.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_input_fields() : array;

	/**
	 * Defines the output field configuration.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_output_fields() : array;


	/**
	 * Defines the data modification closure.
	 *
	 * @since 0.4.0
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable;

}
