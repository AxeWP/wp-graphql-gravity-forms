<?php
/**
 * GraphQL Object Type - PasswordInputProperty
 * An individual input in the Password field 'inputs' property.
 *
 * @see https://docs.gravityforms.com/gf_field_password/
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use InputProperty classes.
 * @since   0.3.0 Add isHidden property.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

/**
 * Class - PasswordInputProperty
 */
class PasswordInputProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PasswordInputProperty';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the password field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputIdProperty::get(),
			InputProperty\InputIsHiddenProperty::get(),
			InputProperty\InputLabelProperty::get(),
			InputProperty\InputPlaceholderProperty::get(),
		);
	}
}
