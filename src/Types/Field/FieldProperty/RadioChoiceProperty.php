<?php
/**
 * GraphQL Object Type - RadioChoiceProperty
 * An individual property for the 'choices' Radio field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ChoiceProperty;

/**
 * Class - RadioChoiceProperty
 */
class RadioChoiceProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'RadioChoiceProperty';

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
						'isOtherChoice' => [
							'type'        => 'Boolean',
							'description' => __( 'Indicates the radio button item is the “Other” choice.', 'wp-graphql-gravity-forms' ),
						],
					],
				),
			],
		);
	}
}
