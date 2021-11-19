<?php
/**
 * Abstract Input Type
 *
 * @package WPGraphQL\GF\Type\Input;
 * @since 0.7.0
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractInput
 */
abstract class AbstractInput extends AbstractType {
	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_input_type(
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

	/**
	 * Gets the properties for the type.
	 */
	abstract public static function get_fields() : array;
}
