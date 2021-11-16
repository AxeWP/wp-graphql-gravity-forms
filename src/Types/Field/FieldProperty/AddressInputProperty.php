<?php
/**
 * GraphQL Object Type - AddressInputProperty
 * An individual property for the 'input' Address field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Field\FieldProperty\InputProperty;

/**
 * Class - AddressInputProperty
 */
class AddressInputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'AddressInputProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the address field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			AutocompleteAttributeProperty::get(),
			DefaultValueProperty::get(),
			PlaceholderProperty::get(),
			LabelProperty::get(),
			InputProperty\InputCustomLabelProperty::get(),
			InputProperty\InputIdProperty::get(),
			InputProperty\InputIsHiddenProperty::get(),
			InputProperty\InputKeyProperty::get(),
			InputProperty\InputNameProperty::get(),
		);
	}
}
