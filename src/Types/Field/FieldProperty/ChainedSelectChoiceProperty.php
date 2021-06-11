<?php
/**
 * GraphQL Object Type - ChainedSelectChoiceProperty
 * An individual property for the 'choices' Chained Select field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperty;
 * @since   0.0.1
 * @since   0.2.0 Use refactored ChoiceProperty fields.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty;

use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ChoiceProperty;

/**
 * Class - ChainedSelectChoiceProperty
 */
class ChainedSelectChoiceProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ChainedSelectChoiceProperty';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms Chained Select field choice property.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return array_merge(
			ChoiceProperty\ChoiceIsSelectedProperty::get(),
			ChoiceProperty\ChoiceTextProperty::get(),
			ChoiceProperty\ChoiceValueProperty::get(),
			[
				'choices' => [
					'type'        => [ 'list_of' => self::$type ],
					'description' => __( 'Choices used to populate the dropdown field. These can be nested multiple levels deep.', 'wp-graphql-gravity-forms' ),
				],
			],
		);
	}
}
