<?php
/**
 * Abstract ValueProperty type.
 *
 * @since 0.5.0
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use WPGraphQL\AppContext;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\ValueProperty;

/**
 * Class - AbstractValueProperty
 */
abstract class AbstractValueProperty implements Hookable, Type, ValueProperty {
	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type;

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name;

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_field(
			static::$type,
			static::$field_name,
			[
				'type'        => $this->get_field_type(),
				'description' => $this->get_type_description(),
				'resolve'     => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
					if ( ! isset( $root['source'] ) || ! is_array( $root['source'] ) ) {
						return null;
					}
					$value = static::get( $root['source'], $root );
					return $value;
				},
			]
		);
	}
}
