<?php
/**
 * GraphQL Object Type - RadioField
 *
 * @see https://docs.gravityforms.com/gf_field_radio/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - RadioField
 */
class RadioField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'RadioField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'radio';


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
				'description' => __( 'Gravity Forms Radio field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\AdminLabelProperty::get(),
					FieldProperty\AdminOnlyProperty::get(),
					FieldProperty\AllowsPrepopulateProperty::get(),
					FieldProperty\ChoicesProperty::get(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\EnableChoiceValueProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\LabelProperty::get(),
					FieldProperty\NoDuplicatesProperty::get(),
					FieldProperty\SizeProperty::get(),
					FieldProperty\VisibilityProperty::get(),
					[
						'enableOtherChoice' => [
							'type'        => 'Boolean',
							'description' => __( 'Indicates whether the \'Enable "other" choice\' option is checked in the editor.', 'wp-graphql-gravity-forms' ),
						],
					],
				),
			]
		);
	}
}
