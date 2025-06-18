<?php
/**
 * GraphQL Object Type - Field error.
 *
 * @package WPGraphQL\GF\Type
 * @since   0.0.1
 * @since   0.4.0 add `id` property.
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPObject;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Loader\FormFieldsLoader;
use WPGraphQL\GF\Type\WPInterface\FormField;
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
			'id'                 => [
				'type'        => 'Float',
				'description' => static fn () => __( 'The field with the associated error message.', 'wp-graphql-gravity-forms' ),
			],
			'message'            => [
				'type'        => 'String',
				'description' => static fn () => __( 'Error message.', 'wp-graphql-gravity-forms' ),
			],
			'connectedFormField' => [
				'type'        => FormField::$type,
				'description' => static fn () => __( 'The form field that the error is connected to.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( empty( $source['id'] ) || empty( $source['formId'] ) ) {
						return null;
					}

					$id_for_loader = (string) $source['formId'] . ':' . (string) $source['id'];

					return $context->get_loader( FormFieldsLoader::$name )->load_deferred( $id_for_loader );
				},
			],
		];
	}
}
