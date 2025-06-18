<?php
/**
 * GraphQL Object Type - Gravity Forms Entry Order Item option.
 *
 * @package WPGraphQL\GF\Type\WPObject\Order
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPObject\Order;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Loader\FormFieldsLoader;
use WPGraphQL\GF\Type\WPInterface\FormField;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - OrderItemOption
 */
class OrderItemOption extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfOrderItemOption';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'An option on an Order item.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'connectedFormField' => [
				'type'        => FormField::$type,
				'description' => static fn () => __( 'The form field that the order item is connected to', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					if ( ! isset( $context->gfForm ) || ! isset( $source['id'] ) ) {
						return null;
					}

					$id_for_loader = (string) $context->gfForm->databaseId . ':' . (string) $source['id'];

					return $context->get_loader( FormFieldsLoader::$name )->load_deferred( $id_for_loader );
				},
			],
			'fieldLabel'         => [
				'type'        => 'String',
				'description' => static fn () => __( 'The option\'s field label.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => $source['field_label'] ?? null,
			],
			'name'               => [
				'type'        => 'String',
				'description' => static fn () => __( 'The option name.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => $source['option_name'] ?? null,
			],
			'optionLabel'        => [
				'type'        => 'String',
				'description' => static fn () => __( 'The option label.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => $source['option_label'] ?? null,
			],
			'price'              => [
				'type'        => 'Float',
				'description' => static fn () => __( 'The option price.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => $source['price'] ?? null,
			],
		];
	}
}
