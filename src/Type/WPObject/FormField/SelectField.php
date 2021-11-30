<?php
/**
 * GraphQL Object Type - SelectField
 *
 * @see https://docs.gravityforms.com/gf_field_select/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - SelectField
 */
class SelectField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SelectField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'select';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Select field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\AutocompleteAttributeProperty::get(),
			FieldProperty\ChoicesProperty::get(),
			FieldProperty\DefaultValueProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\EnableAutocompleteProperty::get(),
			FieldProperty\EnableChoiceValueProperty::get(),
			FieldProperty\EnableEnhancedUiProperty::get(),
			FieldProperty\EnablePriceProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\NoDuplicatesProperty::get(),
			FieldProperty\PlaceholderProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			... static::get_fields_from_gf_settings(),
		);
	}
}
