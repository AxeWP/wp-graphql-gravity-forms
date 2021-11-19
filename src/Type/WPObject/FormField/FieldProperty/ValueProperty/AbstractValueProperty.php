<?php
/**
 * Abstract ValueProperty type.
 *
 * @since 0.5.0
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

use GF_Field;
use WPGraphQL\AppContext;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractValueProperty
 */
abstract class AbstractValueProperty extends AbstractType {
	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name;

	/**
	 * {@inheritDoc}
	 *
	 * @var boolean
	 */
	public static bool $should_load_eagerly = true;

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_field(
			static::$type,
			static::$field_name,
			static::prepare_config(
				[
					'type'            => static::get_field_type(),
					'description'     => static::get_description(),
					'resolve'         => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
						if ( ! isset( $root['source'] ) || ! is_array( $root['source'] ) ) {
							return null;
						}

						return static::get( $root['source'], $root );
					},
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);
	}

	/**
	 * Get the field value.
	 *
	 * @todo stop returning array once fieldValue is removed.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return mixed Entry field value.
	 */
	abstract public static function get( array $entry, GF_Field $field );

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return string|array
	 */
	abstract public static function get_field_type();
}
