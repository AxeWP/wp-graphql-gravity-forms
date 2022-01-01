<?php
/**
 * Abstract Input Type
 *
 * @package WPGraphQL\GF\Type\Input;
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Interfaces\Type;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractInput
 */
abstract class AbstractInput implements Registrable, Type, TypeWithFields {
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
		register_graphql_input_type(
			static::$type,
			[
				'description' => static::get_description(),
				'fields'      => static::get_fields(),
			]
		);
	}
}
