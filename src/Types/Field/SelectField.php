<?php
/**
 * GraphQL Object Type - SelectField
 *
 * @see https://docs.gravityforms.com/gf_field_select/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - SelectField
 */
class SelectField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SelectField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'select';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms Select field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\AutocompleteAttributeProperty::get(),
			FieldProperty\ChoicesProperty::get(),
			FieldProperty\DefaultValueProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\EnableAutocompleteProperty::get(),
			FieldProperty\EnableChoiceValueProperty::get(),
			FieldProperty\EnableEnhancedUiProperty::get(),
			FieldProperty\EnablePriceProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\PlaceholderProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
		);
	}
}
