<?php
/**
 * Abstract Field Value type.
 *
 * @since 0.5.0
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use WPGraphQL\AppContext;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\GF\Interfaces\FieldValue;
use WPGraphQL\GF\Type\AbstractType;
use WPGraphQL\GF\Model\Entry;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractFieldValue
 */
abstract class AbstractFieldValue extends AbstractType implements FieldValue {
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
						if ( ! $root instanceof GF_Field || ! isset( $context->gfEntry ) || ! $context->gfEntry instanceof Entry ) {
							return null;
						}
						if ( ! isset( $context->gfEntry->entryValues ) ) {
							return null;
						}

						return static::get( $context->gfEntry->entryValues, $root );
					},
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);
	}
}