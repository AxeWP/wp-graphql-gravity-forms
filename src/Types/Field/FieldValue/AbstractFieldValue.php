<?php
/**
 * Abstract FieldValue Type
 *
 * @package WPGraphQLGravityForms\Types\FieldValue
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\FieldValue;

/**
 * Class - AbstractFieldValue
 */
abstract class AbstractFieldValue implements Hookable, Type, FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type;

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
		register_graphql_object_type(
			static::$type,
			[
				'description' => $this->get_type_description(),
				'fields'      => $this->get_properties(),
			]
		);
	}

}
