<?php
/**
 * GraphQL Object Type - ChoiceProperty
 * An individual property for the 'choices' field property.
 *
 * @see https://docs.gravityforms.com/field-object/#basic-properties
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Refactor ChoiceProperty for reuse.
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Types\AbstractObject;

/**
 * Class - ChoiceProperty
 */
class ChoiceProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ChoiceProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms choice property.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			ChoiceProperty\ChoiceIsSelectedProperty::get(),
			ChoiceProperty\ChoiceTextProperty::get(),
			ChoiceProperty\ChoiceValueProperty::get(),
		);
	}
}
