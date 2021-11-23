<?php
/**
 * GraphQL Object Type - NameInputProperty
 * An individual property for the 'input' Name field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\InputProperty;

/**
 * Class - NameInputProperty
 */
class NameInputProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'NameInputProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'An array containing the the individual properties for each element of the name field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
