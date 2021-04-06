<?php
/**
 * GraphQL Object Type - PasswordInputProperty
 * An individual input in the Password field 'inputs' property.
 *
 * @see https://docs.gravityforms.com/gf_field_password/
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use InputProperty classes.
 * @since   0.3.0 Add isHidden property.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

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
				'description' => __( 'An array containing the the individual properties for each element of the password field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					InputProperty\InputCustomLabelProperty::get(),
					InputProperty\InputIdProperty::get(),
					InputProperty\InputIsHiddenProperty::get(),
					InputProperty\InputLabelProperty::get(),
					InputProperty\InputPlaceholderProperty::get(),
				),
			]
		);
	}
}
