<?php
/**
 * GraphQL Object Type - NameValuePropery
 * An individual property for the 'value' Name field property.
 *
 * @package WPGraphQL\GF\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Types\Field\FieldProperty\ValueProperty;

use WPGraphQL\GF\Types\AbstractObject;

/**
 * Class - NameValueProperty
 */
class NameValueProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NameValueProperty';

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description(): string {
		return __( 'The individual properties for each element of the Name value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields(): array {
		return [
			'prefix' => [
				'type'        => 'String',
				'description' => __( 'Prefix, such as Mr., Mrs. etc.', 'wp-graphql-gravity-forms' ),
			],
			'first'  => [
				'type'        => 'String',
				'description' => __( 'First name.', 'wp-graphql-gravity-forms' ),
			],
			'middle' => [
				'type'        => 'String',
				'description' => __( 'Middle name.', 'wp-graphql-gravity-forms' ),
			],
			'last'   => [
				'type'        => 'String',
				'description' => __( 'Last name.', 'wp-graphql-gravity-forms' ),
			],
			'suffix' => [
				'type'        => 'String',
				'description' => __( 'Suffix, such as Sr., Jr. etc.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
