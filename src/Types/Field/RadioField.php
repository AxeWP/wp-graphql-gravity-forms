<?php
/**
 * GraphQL Object Type - RadioField
 *
 * @see https://docs.gravityforms.com/gf_field_radio/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and use RadioChoiceProperty.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - RadioField
 */
class RadioField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'RadioField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'radio';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Radio field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\EnableChoiceValueProperty::get(),
			FieldProperty\EnablePriceProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'choices'           => [
					'type'        => [ 'list_of' => FieldProperty\RadioChoiceProperty::TYPE ],
					'description' => __( 'Choices used to populate the dropdown field. These can be nested multiple levels deep.', 'wp-graphql-gravity-forms' ),
				],
				'enableOtherChoice' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates whether the \'Enable "other" choice\' option is checked in the editor.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
