<?php
/**
 * GraphQL Object Type - CheckboxInputProperty
 * An individual property for the 'inputs' Checkbox field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use InputProperty classes.
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Field\FieldProperty\InputProperty;

/**
 * Class - CheckboxInputProperty
 */
class CheckboxInputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxInputProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the checkbox field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			InputProperty\InputIdProperty::get(),
			LabelProperty::get(),
			InputProperty\InputNameProperty::get(),
		);
	}
}
