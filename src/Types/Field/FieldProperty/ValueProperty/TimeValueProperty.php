<?php
/**
 * GraphQL Object Type - TimeValuePropery
 * An individual property for the 'value' Time field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use WPGraphQLGravityForms\Types\AbstractType;

/**
 * Class - TimeValueProperty
 */
class TimeValueProperty extends AbstractType {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'TimeValueProperty';

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description(): string {
		return __( 'The individual properties for each element of the Time value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_type_fields(): array {
		return [
			'displayValue' => [
				'type'        => 'String',
				'description' => __( 'The full display value. Example: "08:25 am".', 'wp-graphql-gravity-forms' ),
			],
			'hours'        => [
				'type'        => 'String',
				'description' => __( 'The hours, in this format: hh.', 'wp-graphql-gravity-forms' ),
			],
			'minutes'      => [
				'type'        => 'String',
				'description' => __( 'The minutes, in this format: mm.', 'wp-graphql-gravity-forms' ),
			],
			'amPm'         => [
				'type'        => 'String',
				'description' => __( 'AM or PM.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
