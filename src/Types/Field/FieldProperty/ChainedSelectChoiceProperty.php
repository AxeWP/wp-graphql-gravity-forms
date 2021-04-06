<?php
/**
 * GraphQL Object Type - ChainedSelectChoiceProperty
 * An individual property for the 'choices' Chained Select field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use refactored ChoiceProperty fields.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ChoiceProperty;

/**
 * Class - ChainedSelectChoiceProperty
 */
class ChainedSelectChoiceProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'ChainedSelectChoiceProperty';

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
				'description' => __( 'Gravity Forms Chained Select field choice property.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					ChoiceProperty\ChoiceIsSelectedProperty::get(),
					ChoiceProperty\ChoiceTextProperty::get(),
					ChoiceProperty\ChoiceValueProperty::get(),
					[
						'choices' => [
							'type'        => [ 'list_of' => self::TYPE ],
							'description' => __( 'Choices used to populate the dropdown field. These can be nested multiple levels deep.', 'wp-graphql-gravity-forms' ),
						],
					],
				),
			]
		);
	}
}
