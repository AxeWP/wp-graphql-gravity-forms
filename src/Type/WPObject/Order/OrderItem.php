<?php
/**
 * GraphQL Object Type - Gravity Forms Entry Order Item
 *
 * @package WPGraphQL\GF\Type\WPObject\Order
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPObject\Order;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\Enum\CurrencyEnum;
use WPGraphQL\GF\Type\WPInterface\FormField;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - OrderItem
 */
class OrderItem extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfOrderItem';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The entry order item.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'section'            => [
				'type'        => 'String',
				'description' => __( 'The section this order item belongs to.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => $source['belongs_to'] ?? null,
			],
			'currency'           => [
				'type'        => CurrencyEnum::$type,
				'description' => __( 'The currency used for the order item', 'wp-graphql-gravity-forms' ),
			],
			'description'        => [
				'type'        => 'String',
				'description' => __( 'The item description', 'wp-graphql-gravity-forms' ),
			],
			'isDiscount'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this is a discount item', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source['is_discount'] ),
			],
			'isLineItem'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this is a line item', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['is_line_item'] ),
			],
			'isRecurring'        => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this is a recurring item', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['is_recurring'] ),
			],
			'isSetupFee'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this is a setup fee', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['is_setup'] ),
			],
			'isShipping'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this is a shipping fee', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['is_shipping'] ),
			],
			'isTrial'            => [
				'type'        => 'Boolean',
				'description' => __( 'Whether this is a trial item', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['is_trial'] ),
			],
			'name'               => [
				'type'        => 'String',
				'description' => __( 'The item name', 'wp-graphql-gravity-forms' ),
			],
			'options'            => [
				'type'        => [ 'list_of' => OrderItemOption::$type ],
				'description' => __( 'The item options', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source['options'] ) ? $source['options'] : null,
			],
			'price'              => [
				'type'        => 'Float',
				'description' => __( 'The item price', 'wp-graphql-gravity-forms' ),
			],
			'quantity'           => [
				'type'        => 'Float',
				'description' => __( 'The item quantity', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => isset( $source['quantity'] ) ? (float) $source['quantity'] : null,
			],
			'subtotal'           => [
				'type'        => 'Float',
				'description' => __( 'The item subtotal', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => isset( $source['sub_total'] ) ? (float) $source['sub_total'] : null,
			],
			'connectedFormField' => [
				'type'        => FormField::$type,
				'description' => __( 'The form field that the order item is connected to', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					return GFUtils::get_field_by_id( $context->gfForm->form, $source['id'] );
				},
			],
		];
	}
}
