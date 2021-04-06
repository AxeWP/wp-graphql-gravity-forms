<?php
/**
 * GraphQL Object Type - CheckboxInputProperty
 * An individual property for the 'inputs' Checkbox field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use InputProperty classes.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

/**
 * Class - CheckboxInputProperty
 */
class CheckboxInputProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'CheckboxInputProperty';

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
			self::TYPE,
			[
				'description' => __( 'An array containing the the individual properties for each element of the checkbox field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					InputProperty\InputIdProperty::get(),
					InputProperty\InputLabelProperty::get(),
					InputProperty\InputNameProperty::get(),
				),
			]
		);
	}
}
