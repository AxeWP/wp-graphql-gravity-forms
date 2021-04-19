<?php
/**
 * GraphQL Object Type - RadioChoiceProperty
 * An individual property for the 'choices' Radio field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.2.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\Field\FieldProperty\ChoiceProperty;

/**
 * Class - RadioChoiceProperty
 */
class RadioChoiceProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'RadioChoiceProperty';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms Chained Select field choice property.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			ChoiceProperty\ChoiceIsSelectedProperty::get(),
			ChoiceProperty\ChoiceTextProperty::get(),
			ChoiceProperty\ChoiceValueProperty::get(),
			[
				'isOtherChoice' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates the radio button item is the “Other” choice.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
