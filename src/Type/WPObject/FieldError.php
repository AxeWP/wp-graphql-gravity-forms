<?php
/**
 * GraphQL Object Type - Field error.
 *
 * @package WPGraphQL\GF\Type
 * @since   0.0.1
 * @since   0.4.0 add `id` property.
 */

namespace WPGraphQL\GF\Type\WPObject;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FieldError
 */
class FieldError extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FieldError';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Field error.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'id'      => [
				'type'        => 'Float',
				'description' => __( 'The field with the associated error message.', 'wp-graphql-gravity-forms' ),
			],
			'message' => [
				'type'        => 'String',
				'description' => __( 'Error message.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
