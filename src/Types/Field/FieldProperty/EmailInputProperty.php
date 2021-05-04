<?php
/**
 * GraphQL Object Type - EmailInputProperty
 * An individual input in the Email field 'inputs' property.
 *
 * @see https://docs.gravityforms.com/gf_field_email/
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use InputProperty classes.
 * @since   0.3.0 Add isHidden property.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - EmailInputProperty
 */
class EmailInputProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EmailInputProperty';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the Email field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			AutocompleteAttributeProperty::get(),
			DefaultValueProperty::get(),
			LabelProperty::get(),
			PlaceholderProperty::get(),
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputIdProperty::get(),
			InputProperty\InputNameProperty::get(),
			/**
			 * Deprecated field properties.
			 *
			 * @since 0.2.0
			 */

			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputIsHiddenProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputKeyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
