<?php
/**
 * Abstract Input Type
 *
 * @package WPGraphQLGravityForms\Types\Input;
 * @since 0.7.0
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Types\AbstractType;

/**
 * Class - AbstractInput
 */
abstract class AbstractInput extends AbstractType {
	/**
	 * Register Input type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_input_type(
			static::$type,
			$this->get_type_config(
				[
					'description' => $this->get_type_description(),
					'fields'      => $this->prepare_fields(),
				]
			)
		);
	}
}
