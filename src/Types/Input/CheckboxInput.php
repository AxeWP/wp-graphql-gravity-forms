<?php
/**
 * GraphQL Input Type - CheckboxInput
 * Input fields for a single checkbox.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - CheckboxInput
 */
class CheckboxInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'CheckboxInput';

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
					'inputId' => [
						'type'        => 'Float',
						'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
					],
					'value'   => [
						'type'        => 'String',
						'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
