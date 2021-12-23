<?php
/**
 * Abstract GraphQL Type.
 *
 * @package WPGraphQL\GF\Type
 * @since 0.7.0
 */

namespace WPGraphQL\GF\Type;

use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\GF\Interfaces\Type;

/**
 * Class - AbstractType
 */
abstract class AbstractType implements Registrable, Type {
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
}
