<?php
/**
 * GraphQL Object Type - Field error.
 *
 * @package WPGraphQLGravityForms\Types\FieldError
 * @since   0.0.1
 * @since   0.4.0 add `id` property.
 */

namespace WPGraphQLGravityForms\Types\FieldError;

use WPGraphQLGravityForms\Types\AbstractType;

/**
 * Class - FieldError
 */
class FieldError extends AbstractType {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FieldError';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Field error.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'id'      => [
				'type'        => 'Float',
				'description' => __( 'The field with the associated error message', 'wp-graphql-gravity-forms' ),
			],
			'message' => [
				'type'        => 'String',
				'description' => __( 'Error message.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
