<?php
/**
 * GraphQL Object Type - AddressInputProperty
 * An individual property for the 'input' Address field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - AddressInputProperty
 */
class AddressInputProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'AddressInputProperty';

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
				'description' => __( 'An array containing the the individual properties for each element of the address field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					InputProperty\InputCustomLabelProperty::get(),
					InputProperty\InputDefaultValueProperty::get(),
					InputProperty\InputIdProperty::get(),
					InputProperty\InputIsHiddenProperty::get(),
					InputProperty\InputLabelProperty::get(),
					InputProperty\InputNameProperty::get(),
					InputProperty\InputPlaceholderProperty::get(),
					/**
					 * Deprecated field properties.
					 *
					 * @since 0.2.0
					 */

					// translators: Gravity Forms Field input property.
					Utils::deprecate_property( InputProperty\InputKeyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
				),
			],
		);
	}
}
