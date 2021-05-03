<?php
/**
 * GraphQL Object Type - NameInputProperty
 * An individual property for the 'input' Name field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\Field\FieldProperty\InputProperty;

/**
 * Class - NameInputProperty
 */
class NameInputProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NameInputProperty';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the name field.', 'wp-graphql-gravity-forms' );
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
			[
				'choices' => [
					'type'        => [ 'list_of' => ChoiceProperty::$type ],
					'description' => __( 'This array only exists when the Prefix field is used. It holds the prefix options that display in the drop down. These have been chosen in the admin.', 'wp-graphql-gravity-forms' ),
				],
			],
			[
				'enableChoiceValue' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates whether the choice has a value, not just the text. This is only available for the Prefix field.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
