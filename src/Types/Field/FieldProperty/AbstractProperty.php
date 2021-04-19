<?php
/**
 * Abstract property type.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
/**
 * Class - AbstractProperty
 */
abstract class AbstractProperty implements Hookable, Type {
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

	/**
	 * Sets the Field type description.
	 *
	 * @return string
	 */
	abstract protected function get_type_description() : string;

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	abstract protected function get_properties() : array;
}
