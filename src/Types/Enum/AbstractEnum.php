<?php
/**
 * Abstract Enum Type
 *
 * @package WPGraphQLGravityForms\Types\Enum
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

use WPGraphQLGravityForms\Types\AbstractType;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Abstract Class - Abstract Enum
 */
abstract class AbstractEnum extends AbstractType {
	/**
	 * Registers Enum type.
	 */
	public function register_type() : void {
		if ( method_exists( $this, 'register' ) ) {
			_deprecated_function( 'register', '0.6.4', 'register_type' );

			$this->register();
		}

		register_graphql_enum_type(
			static::$type,
			$this->get_type_config(
				[
					'description' => $this->get_type_description(),
					'values'      => $this->prepare_values(),
				]
			)
		);
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @since 0.4.0
	 */
	abstract public function set_values() : array;

	/**
	 * Filters and sorts the values before register().
	 */
	private function prepare_values() : array {
		/**
		 * Pass the values through a filter.
		 */

		$values = apply_filters( 'wp_graphql_' . Utils::to_snake_case( static::$type ) . '_values', $this->set_values() );

		/**
		 * Sort the values alpahbetically by key.
		 */
		ksort( $values );

		return $values;
	}

}
