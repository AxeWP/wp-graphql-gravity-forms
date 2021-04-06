<?php
/**
 * Gravity Forms field.
 *
 * @see https://docs.gravityforms.com/field-object/
 * @see https://docs.gravityforms.com/gf_field/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\GraphQLInterface\FormFieldInterface;
/**
 * Class - AbstractField
 */
abstract class AbstractField implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type;

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type;

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
				'interfaces'  => [ FormFieldInterface::TYPE ],
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

	/**
	 * Get the global properties that apply to all GF field types.
	 *
	 * @return array
	 */
	protected function get_global_properties() : array {
		return FormFieldInterface::get_properties();
	}

	/**
	 * Get the custom properties.
	 *
	 * @return array
	 */
	protected function get_custom_properties() : array {
		/**
		 * Add GraphQL fields for custom field properties.
		 *
		 * @param array Additional GraphQL field definitions.
		 * @param array The type of Gravity Forms field.
		 */
		return apply_filters( 'wp_graphql_gf_custom_properties', [], static::$gf_type );
	}
}
