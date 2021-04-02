<?php
/**
 * GraphQL Object Type - ChoiceProperty
 * An individual property for the 'choices' field property.
 *
 * @see https://docs.gravityforms.com/field-object/#basic-properties
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Refactor ChoiceProperty for reuse.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - ChoiceProperty
 */
class ChoiceProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'ChoiceProperty';

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
				'description' => __( 'Gravity Forms choice property.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					ChoiceProperty\ChoiceIsSelectedProperty::get(),
					ChoiceProperty\ChoiceTextProperty::get(),
					ChoiceProperty\ChoiceValueProperty::get(),
				),
			]
		);
	}
}
