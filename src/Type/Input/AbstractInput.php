<?php
/**
 * Abstract Input Type
 *
 * @package WPGraphQL\GF\Type\Input;
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Interfaces\TypeWithDescription;
use WPGraphQL\GF\Interfaces\TypeWithFields;
use WPGraphQL\GF\Type\AbstractType;

/**
 * Class - AbstractInput
 */
abstract class AbstractInput extends AbstractType implements TypeWithDescription, TypeWithFields {
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

		register_graphql_input_type( static::$type, $config );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		return [
			'description' => static::get_description(),
			'fields'      => static::get_fields(),
		];
	}
}
