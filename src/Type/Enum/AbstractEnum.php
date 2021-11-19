<?php
/**
 * Abstract Enum Type
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since 0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\GF\Utils\Utils;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Abstract Class - Abstract Enum
 */
abstract class AbstractEnum extends AbstractType {
	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_enum_type(
			static::$type,
			static::prepare_config(
				[
					'description'     => static::get_description(),
					'values'          => static::get_values(),
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);
	}

	/**
	 * Gets the Enum type values.
	 */
	abstract public static function get_values() : array;
}
