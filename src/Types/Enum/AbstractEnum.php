<?php
/**
 * Abstract Enum Type
 *
 * @package WPGraphQLGravityForms\Types\Enum
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Enum;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Abstract Class - Abstract Enum
 */
abstract class AbstractEnum implements Hookable, Enum {
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
		add_action( 'graphql_register_types', [ $this, 'register' ] );
	}

	/**
	 * Registers Enum type.
	 */
	public function register() : void {
		register_graphql_enum_type(
			static::$type,
			[
				'description' => $this->get_type_description(),
				'values'      => $this->prepare_values(),
			],
		);
	}

	/**
	 * Filters and sorts the values before register().
	 *
	 * @return array
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
