<?php
/**
 * Abstract GraphQL Interface Type.
 *
 * @package WPGraphQL\GF\Type
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface;

use WPGraphQL\GF\Interfaces\TypeWithDescription;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractType
 */
abstract class AbstractInterface extends AbstractType implements TypeWithDescription, TypeWithFields {
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
	 *
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry .
	 */
	public static function register( TypeRegistry $type_registry = null ): void {
		$config = static::get_type_config( $type_registry );

		register_graphql_interface_type( static::$type, $config );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config( TypeRegistry $type_registry = null ): array {
		return [
			'description'     => static::get_description(),
			'fields'          => static::get_fields(),
			'eagerlyLoadType' => static::$should_load_eagerly,
		];
	}
}
