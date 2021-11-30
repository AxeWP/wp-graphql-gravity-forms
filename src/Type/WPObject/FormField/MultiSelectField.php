<?php
/**
 * GraphQL Object Type - MultiSelectField
 *
 * @see https://docs.gravityforms.com/gf_field_multiselect/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and use ChoicesProperty.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * MultiSelect field.
 *
 * @see https://docs.gravityforms.com/gf_field_multiselect/
 */
class MultiSelectField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'MultiSelectField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'multiselect';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Multi-Select field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\ChoicesProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\EnableChoiceValueProperty::get(),
			FieldProperty\EnableEnhancedUiProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			... static::get_fields_from_gf_settings(),
		);
	}
}
