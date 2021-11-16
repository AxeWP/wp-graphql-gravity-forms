<?php
/**
 * GraphQL Object Type - NameInputProperty
 * An individual property for the 'input' Name field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty;

use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Field\FieldProperty\InputProperty;

/**
 * Class - NameInputProperty
 */
class NameInputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NameInputProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'An array containing the the individual properties for each element of the name field.', 'wp-graphql-gravity-forms' );
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
