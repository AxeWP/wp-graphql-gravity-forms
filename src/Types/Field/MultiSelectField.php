<?php
/**
 * GraphQL Object Type - MultiSelectField
 *
 * @see https://docs.gravityforms.com/gf_field_multiselect/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * MultiSelect field.
 *
 * @see https://docs.gravityforms.com/gf_field_multiselect/
 */
class MultiSelectField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'MultiSelectField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'multiselect';

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
				'description' => __( 'Gravity Forms Multi-Select field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\DescriptionPlacementProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\EnableChoiceValueProperty::get(),
					FieldProperty\EnableEnhancedUiProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						'choices' => [
							'type'        => [ 'list_of' => FieldProperty\MultiSelectChoiceProperty::TYPE ],
							'description' => __( 'The individual properties for each item in the multi-select.', 'wp-graphql-gravity-forms' ),
						],
					]
				),
			]
		);
	}
}
