<?php
/**
 * GraphQL Object Type - Field error.
 *
 * @package WPGraphQLGravityForms\Types\FieldError
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\FieldError;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - FieldError
 */
class FieldError implements Hookable, Type {
	const TYPE = 'FieldError';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Field error.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'message' => [
						'type'        => 'String',
						'description' => __( 'Error message.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
