<?php
/**
 * Abstract Enum Type
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

use WPGraphQL\GF\Interfaces\Enum;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Interfaces\Type;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Abstract Class - Abstract Enum
 */
abstract class AbstractEnum implements Enum, Registrable, Type {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type;

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_enum_type(
			static::$type,
			[
				'description' => static::get_description(),
				'values'      => static::get_values(),
			]
		);
	}
}
