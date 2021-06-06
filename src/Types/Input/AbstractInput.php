<?php
/**
 * Abstract Input Type
 *
 * @package WPGraphQLGravityForms\Types\Input;
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - AbstractInput
 */
abstract class AbstractInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type;

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Input type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_input_type(
			static::$type,
			[
				'description' => $this->get_type_description(),
				'fields'      => $this->get_properties(),
			]
		);
	}

	/**
	 * Sets the Field type description.
	 *
	 * @return string
	 */
	abstract protected function get_type_description() : string;

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	abstract protected function get_properties() : array;
}
