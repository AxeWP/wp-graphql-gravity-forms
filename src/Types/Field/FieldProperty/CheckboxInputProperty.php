<?php
/**
 * GraphQL Object Type - CheckboxInputProperty
 * An individual property for the 'inputs' Checkbox field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use InputProperty classes.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

/**
 * Class - CheckboxInputProperty
 */
class CheckboxInputProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxInputProperty';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the checkbox field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			InputProperty\InputIdProperty::get(),
			InputProperty\InputLabelProperty::get(),
			InputProperty\InputNameProperty::get(),
		);
	}
}
