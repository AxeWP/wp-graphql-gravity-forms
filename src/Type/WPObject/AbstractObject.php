<?php
/**
 * Abstract GraphQL Object Type.
 *
 * @package WPGraphQL\GF\Type
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject;

use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Interfaces\Type;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractType
 */
abstract class AbstractObject implements Registrable, Type, TypeWithFields {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormField';

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
	 *
	 * @param  TypeRegistry $type_registry .
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			[
				'description'     => static::get_description(),
				'fields'          => static::get_fields(),
				'eagerlyLoadType' => static::$should_load_eagerly,
			]
		);
	}
}
