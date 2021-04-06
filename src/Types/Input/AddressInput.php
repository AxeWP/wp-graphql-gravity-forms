<?php
/**
 * GraphQL Input Type - AddressInput
 * Input fields for address field.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - AddressInput
 */
class AddressInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'AddressInput';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
	}

	/**
	 * Register input type to GraphQL schema.
	 */
	public function register_input_type() : void {
		register_graphql_input_type(
			self::TYPE,
			[
				'description' => __( 'Input fields for address field.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
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
				],
			]
		);
	}
}
