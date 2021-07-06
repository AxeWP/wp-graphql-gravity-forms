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
			_deprecated_function( 'register', '0.7.0', 'register_type' );

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
	 * Gets the Enum type values.
	 *
	 * @todo convert to abstract function after deprecation is removed.
	 *
	 * @since 0.7.0
	 */
	public function get_values() : array {
		if ( method_exists( $this, 'set_values' ) ) {
			_deprecated_function( 'set_values', '0.7.0', 'get_values' );
			return $this->set_values();
		}
		return [];
	}

	/**
	 * Filters and sorts the values before register().
	 */
	private function prepare_values() : array {
		/**
		 * Pass the values through a filter.
		 */

		$values = apply_filters( 'wp_graphql_' . Utils::to_snake_case( static::$type ) . '_values', $this->get_values() );

		/**
		 * Sort the values alpahbetically by key.
		 */
		ksort( $values );

		return $values;
	}

}
