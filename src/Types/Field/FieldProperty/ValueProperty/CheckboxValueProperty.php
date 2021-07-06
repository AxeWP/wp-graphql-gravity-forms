<?php
/**
 * GraphQL Object Type - CheckboxValuePropery
 * An individual property for the 'value' Checkbox field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use WPGraphQLGravityForms\Types\AbstractObject;

/**
 * Class - CheckboxValueProperty
 */
class CheckboxValueProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxValueProperty';

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description(): string {
		return __( 'The individual properties for each element of the Checkbox value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields(): array {
		return [
			'inputId' => [
				'type'        => 'Float',
				'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'   => [
				'type'        => 'String',
				'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
