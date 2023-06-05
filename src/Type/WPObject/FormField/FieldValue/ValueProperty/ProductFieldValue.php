<?php
/**
 * GraphQL Object Type - ProductFieldValue
 * An individual value property for the Product field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - ProductFieldValue
 */
class ProductFieldValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ProductFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The individual properties for each element of the Product value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'name'     => [
				'type'        => 'String',
				'description' => __( 'The product name.', 'wp-graphql-gravity-forms' ),
			],
			'price'    => [
				'type'        => 'String',
				'description' => __( 'The product price.', 'wp-graphql-gravity-forms' ),
			],
			'quantity' => [
				'type'        => 'Float',
				'description' => __( 'The product quantity.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
