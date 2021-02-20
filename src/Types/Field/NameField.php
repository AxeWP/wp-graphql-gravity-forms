<?php
/**
 * GraphQL Object Type - NameField
 *
 * @see https://docs.gravityforms.com/gf_field_name/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - NameField
 */
class NameField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'NameField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'name';

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
				'description' => __( 'Gravity Forms Name field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\DescriptionPlacementProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputsProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\SubLabelPlacementProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						/**
						 * Possible values: normal, extended, simple
						 */
						'nameFormat' => [
							'type'        => 'String',
							'description' => __( 'Determines the format of the name field.', 'wp-graphql-gravity-forms' ),
						],
						// @TODO: Add placeholders.
					],
					/**
					 * Deprecated field properties.
					 *
					 * @since 0.1.0
					 */

					// translators: Gravity Forms Field type.
					Utils::deprecate_property( FieldProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type. Please use `inputs { name }` instead.', 'wp-graphql-gravity-forms' ), self::TYPE ) ),
				),
			]
		);
	}
}
