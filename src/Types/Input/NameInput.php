<?php
/**
 * GraphQL Input Type - NameInput
 * Input fields for name field.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - NameInput
 */
class NameInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'NameInput';

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
				'description' => __( 'Input fields for name field.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
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
				],
			]
		);
	}
}
