<?php
/**
 * Abstract Input Type
 *
 * @package WPGraphQLGravityForms\Types\Input;
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
		/**
		 * Call deprecated get_properties() function, in case it's used in a child class.
		 *
		 * @since 0.6.4
		 */
		$fields = $this->get_type_fields();
		if ( method_exists( $this, 'get_properties' ) ) {
			_deprecated_function( 'get_properties', '0.6.4', 'get_type_fields' );
			$fields = array_merge( $fields, $this->get_properties() );
		}

		register_graphql_input_type(
			static::$type,
			$this->get_type_config(
				[
					'description' => $this->get_type_description(),
					'fields'      => $fields,
				]
			)
		);
	}

	/**
	 * Gets the properties for the Field. Not abstract, so deprecated child classes don't break.
	 *
	 * @todo convert to abstract class.
	 * @return array
	 */
	public function get_type_fields() : array {
		return [];
	}
}
