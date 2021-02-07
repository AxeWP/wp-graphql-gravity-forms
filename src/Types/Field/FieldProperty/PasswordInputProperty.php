<?php
/**
 * GraphQL Object Type - PasswordInputProperty
 * An individual input in the Password field 'inputs' property.
 *
 * @see https://docs.gravityforms.com/gf_field_password/
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - PasswordInputProperty
 */
class PasswordInputProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'PasswordInputProperty';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms input property.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'id'          => [
						'type'        => 'Float',
						'description' => __( 'The id of the input field.', 'wp-graphql-gravity-forms' ),
					],
					'label'       => [
						'type'        => 'String',
						'description' => __( 'The label for the input.', 'wp-graphql-gravity-forms' ),
					],
					'customLabel' => [
						'type'        => 'String',
						'description' => __( 'The custom label for the input. When set, this is used in place of the label.', 'wp-graphql-gravity-forms' ),
					],
					'placeholder' => [
						'type'        => 'String',
						'description' => __( 'Placeholder text to give the user a hint on how to fill out the field. This is not submitted with the form.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
