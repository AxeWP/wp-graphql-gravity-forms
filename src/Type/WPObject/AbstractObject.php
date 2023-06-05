<?php
/**
 * Abstract GraphQL Object Type.
 *
 * @package WPGraphQL\GF\Type
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject;

use WPGraphQL\GF\Interfaces\TypeWithDescription;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\GF\Type\AbstractType;

/**
 * Class - AbstractType
 */
abstract class AbstractObject extends AbstractType implements TypeWithDescription, TypeWithFields {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type;

	/**
	 * Whether the type should be loaded eagerly by WPGraphQL. Defaults to false.
	 *
	 * Eager load should only be necessary for types that are not referenced directly (e.g. in Unions, Interfaces ).
	 *
	 * @var boolean
	 */
	public static bool $should_load_eagerly = false;

	/**
	 * Register Object type to GraphQL schema.
	 */
	public static function register(): void {
		$config = static::get_type_config();

		register_graphql_object_type( static::$type, $config );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		return [
			'description'     => static::get_description(),
			'fields'          => static::get_fields(),
			'eagerlyLoadType' => static::$should_load_eagerly,
		];
	}
}
