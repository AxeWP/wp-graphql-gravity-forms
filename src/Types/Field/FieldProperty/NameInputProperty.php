<?php
/**
 * GraphQL Object Type - NameInputProperty
 * An individual property for the 'input' Name field property.
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
 * Class - NameInputProperty
 */
class NameInputProperty implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'NameInputProperty';

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
				'description' => __( 'An array containing the the individual properties for each element of the name field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					InputProperty\InputCustomLabelProperty::get(),
					InputProperty\InputDefaultValueProperty::get(),
					InputProperty\InputIdProperty::get(),
					InputProperty\InputIsHiddenProperty::get(),
					InputProperty\InputLabelProperty::get(),
					InputProperty\InputNameProperty::get(),
					InputProperty\InputPlaceholderProperty::get(),
					[
						'choices' => [
							'type'        => [ 'list_of' => ChoiceProperty::TYPE ],
							'description' => __( 'This array only exists when the Prefix field is used. It holds the prefix options that display in the drop down. These have been chosen in the admin.', 'wp-graphql-gravity-forms' ),
						],
					],
					[
						'enableChoiceValue' => [
							'type'        => 'Boolean',
							'description' => __( 'Indicates whether the choice has a value, not just the text. This is only available for the Prefix field.', 'wp-graphql-gravity-forms' ),
						],
					],
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
