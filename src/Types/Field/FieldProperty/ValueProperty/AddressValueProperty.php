<?php
/**
 * GraphQL Object Type - AddressValuePropery
 * An individual property for the 'value' Address field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use WPGraphQLGravityForms\Types\AbstractObject;

/**
 * Class - AddressValueProperty
 */
class AddressValueProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'AddressValueProperty';

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description(): string {
		return __( 'The individual properties for each element of the address value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields(): array {
		return [
			'street'  => [
				'type'        => 'String',
				'description' => __( 'Street address.', 'wp-graphql-gravity-forms' ),
			],
			'lineTwo' => [
				'type'        => 'String',
				'description' => __( 'Address line two.', 'wp-graphql-gravity-forms' ),
			],
			'city'    => [
				'type'        => 'String',
				'description' => __( 'Address city.', 'wp-graphql-gravity-forms' ),
			],
			'state'   => [
				'type'        => 'String',
				'description' => __( 'Address state/region/province name.', 'wp-graphql-gravity-forms' ),
			],
			'zip'     => [
				'type'        => 'String',
				'description' => __( 'Address zip code', 'wp-graphql-gravity-forms' ),
			],
			'country' => [
				'type'        => 'String',
				'description' => __( 'Address country name.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
