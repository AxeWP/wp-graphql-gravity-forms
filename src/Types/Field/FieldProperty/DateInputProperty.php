<?php
/**
 * GraphQL Object Type - DateInputProperty
 * An individual input in the Email field 'inputs' property.
 *
 * @see https://docs.gravityforms.com/gf_field_email/
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.6.3
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - DateInputProperty
 */
class DateInputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'DateInputProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the Email field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			AutocompleteAttributeProperty::get(),
			DefaultValueProperty::get(),
			LabelProperty::get(),
			PlaceholderProperty::get(),
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputIdProperty::get(),
			/**
			 * Deprecated field properties.
			 *
			 * @since 0.6.0
			 */
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( AutocompleteAttributeProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// @since 0.2.0 .
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputIsHiddenProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputKeyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
