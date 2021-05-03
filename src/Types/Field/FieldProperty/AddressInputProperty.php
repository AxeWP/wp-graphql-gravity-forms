<?php
/**
 * GraphQL Object Type - AddressInputProperty
 * An individual property for the 'input' Address field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

/**
 * Class - AddressInputProperty
 */
class AddressInputProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'AddressInputProperty';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the address field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			AutocompleteAttributeProperty::get(),
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputDefaultValueProperty::get(),
			InputProperty\InputIdProperty::get(),
			InputProperty\InputIsHiddenProperty::get(),
			InputProperty\InputKeyProperty::get(),
			InputProperty\InputLabelProperty::get(),
			InputProperty\InputNameProperty::get(),
			InputProperty\InputPlaceholderProperty::get(),
		);
	}
}
