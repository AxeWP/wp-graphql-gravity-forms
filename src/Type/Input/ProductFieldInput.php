<?php
/**
 * GraphQL Input Type - ProductFieldInput
 * Input fields for a product.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.12.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - ProductFieldInput
 */
class ProductFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ProductFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for Product field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'quantity' => [
				'type'        => 'Float',
				'description' => __( 'Product quantity.', 'wp-graphql-gravity-forms' ),
			],
			'price'    => [
				'type'        => 'Float',
				'description' => __( 'Product price.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
