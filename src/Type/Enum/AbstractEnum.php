<?php
/**
 * Abstract Enum Type
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Enum;

use WPGraphQL\GF\Interfaces\Enum;
use WPGraphQL\GF\Interfaces\TypeWithDescription;
use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\GF\Utils\Compat;

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
	 * Gets the Enum values configuration array.
	 *
	 * @return array<string,array{
	 *   description:string|callable():string,
	 *   value:mixed,
	 *   deprecationReason?:callable():string
	 * }>
	 */
	abstract public static function get_values(): array;

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		$config = static::get_type_config();

		register_graphql_enum_type(
			static::$type,
			// @phpstan-ignore argument.type (Narrowing T doesnt work here for some reason)
			Compat::resolve_graphql_config( $config )
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array{
	 *  description: string|callable():string,
	 *  eagerlyLoadType?: bool,
	 *  values:array<string,array{
	 *   description:string|callable():string,
	 *   value:mixed,
	 *   deprecationReason?:callable():string
	 * }>,
	 * }
	 */
	public static function get_type_config(): array {
		return [
			'description' => static fn () => static::get_description(),
			'values'      => static::get_values(),
		];
	}
}
