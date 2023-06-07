<?php
/**
 * GraphQL Interface for a FormField with the `base_price_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithBasePrice
 */
class FieldWithBasePrice extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBasePriceSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'base_price_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'price'          => [
				'type'        => 'Float',
				'description' => __( 'The price of the product.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->basePrice ) ? floatval( preg_replace( '/[^\d\.]/', '', $source->basePrice ) ) : null,
			],
			'formattedPrice' => [
				'type'        => 'String',
				'description' => __( 'The price of the product, prefixed by the currency.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->basePrice ) ? $source->basePrice : null,
			],
		];
	}
}
