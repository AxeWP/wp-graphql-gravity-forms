<?php
/**
 * GraphQL Object Type - InputProperty
 * An individual input for the 'inputs' field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - InputProperty
 */
class InputProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'InputProperty';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms input property.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputDefaultValueProperty::get(),
			InputProperty\InputIdProperty::get(),
			InputProperty\InputLabelProperty::get(),
			InputProperty\InputPlaceholderProperty::get(),
			AutocompleteAttributeProperty::get(),
			/**
			 * Deprecated field properties.
			 *
			 * @since 0.2.0
			 */

			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputIsHiddenProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputKeyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field input property.
			Utils::deprecate_property( InputProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
