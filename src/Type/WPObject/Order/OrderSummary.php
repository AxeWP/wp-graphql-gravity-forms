<?php
/**
 * GraphQL Object Type - Gravity Forms Entry Order
 *
 * @see https://docs.gravityforms.com/confirmation/
 *
 * @package WPGraphQL\GF\Type\WPObject\Order
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPObject\Order;

use WPGraphQL\GF\Type\Enum\CurrencyEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - OrderSummary
 */
class OrderSummary extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfOrderSummary';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The entry order information.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'currency' => [
				'type'        => CurrencyEnum::$type,
				'description' => __( 'The currency used for the order', 'wp-graphql-gravity-forms' ),
			],
			'items'    => [
				'type'        => [ 'list_of' => OrderItem::$type ],
				'description' => __( 'The order item details.', 'wp-graphql-gravity-forms' ),
			],
			'subtotal' => [
				'type'        => 'Float',
				'description' => __( 'The order subtotal.', 'wp-graphql-gravity-forms' ),
			],
			'total'    => [
				'type'        => 'Float',
				'description' => __( 'The order total', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
