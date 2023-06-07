<?php
/**
 * Abstract Enum Type
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

use WPGraphQL\GF\Interfaces\Enum;
use WPGraphQL\GF\Interfaces\TypeWithDescription;
use WPGraphQL\GF\Type\AbstractType;

/**
 * Abstract Class - Abstract Enum
 */
abstract class AbstractEnum extends AbstractType implements Enum, TypeWithDescription {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type;

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		$config = static::get_type_config();

		register_graphql_enum_type( static::$type, $config );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		return [
			'description' => static::get_description(),
			'values'      => static::get_values(),
		];
	}
}
