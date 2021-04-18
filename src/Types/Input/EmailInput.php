<?php
/**
 * GraphQL Input Type - EmailInput
 * Input fields for a single checkbox.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - EmailInput
 */
class EmailInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'EmailInput';

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
				'description' => __( 'Input fields for a single checkbox.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'value'             => [
						'type'        => 'String',
						'description' => __( 'Email input value', 'wp-graphql-gravity-forms' ),
					],
					'confirmationValue' => [
						'type'        => 'String',
						'description' => __( 'Email confirmation input value. Only used when email confirmation is enabled.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
