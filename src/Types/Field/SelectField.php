<?php
/**
 * GraphQL Object Type - SelectField
 *
 * @see https://docs.gravityforms.com/gf_field_select/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - SelectField
 */
class SelectField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'SelectField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'select';

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
				'description' => __( 'Gravity Forms Select field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\ChoicesProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\EnableChoiceValueProperty::get(),
					FieldProperty\EnableEnhancedUiProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\NoDuplicatesProperty::get(),
					FieldProperty\PlaceholderProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\VisibilityProperty::get(),
				),
			]
		);
	}
}
