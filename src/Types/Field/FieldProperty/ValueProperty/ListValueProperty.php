<?php
/**
 * GraphQL Object Type - ListValuePropery
 * An individual property for the 'value' List field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use WPGraphQLGravityForms\Types\Field\FieldProperty\AbstractProperty;

/**
 * Class - ListValueProperty
 */
class ListValueProperty extends AbstractProperty {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ListValueProperty';

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description(): string {
		return __( 'The individual properties for each element of the List value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_properties(): array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Input values', 'wp-graphql-gravity-forms' ),
			],
			'value'  => [
				'type'              => [ 'list_of' => 'String' ],
				'description'       => __( 'Input value', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `values` instead.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
