<?php
/**
 * Abstract GraphQL Type.
 *
 * @package WPGraphQL\GF\Type
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type;

use WPGraphQL\GF\Interfaces\Hookable;
use WPGraphQL\GF\Interfaces\Registrable;

/**
 * Class - AbstractType
 */
abstract class AbstractType implements Hookable, Registrable {
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
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_action( 'graphql_register_types', [ static::class, 'register' ] );
	}

	/**
	 * Gets the WPGraphQL config array used to register the type.
	 */
	abstract public static function get_type_config(): array;
}
