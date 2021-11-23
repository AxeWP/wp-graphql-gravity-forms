<?php
/**
 * Abstract GraphQL Object Type.
 *
 * @package WPGraphQL\GF\Type
 * @since 0.7.0
 */

namespace WPGraphQL\GF\Type\WPObject;

use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractType
 */
abstract class AbstractObject extends AbstractType implements TypeWithFields {
	/**
	 * Register Object type to GraphQL schema.
	 *
	 * @param  TypeRegistry $type_registry .
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			static::prepare_config(
				[
					'description'     => static::get_description(),
					'fields'          => static::get_fields(),
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);
	}
}
