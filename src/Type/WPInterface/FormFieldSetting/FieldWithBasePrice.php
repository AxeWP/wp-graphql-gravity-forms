<?php
/**
 * GraphQL Interface for a FormField with the `base_price_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithBasePrice
 */
class FieldWithBasePrice extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBasePrice';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'base_price_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'price'          => [
				'type'        => 'Float',
				'description' => __( 'The price of the product.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->basePrice ) ? floatval( preg_replace( '/[^\d\.]/', '', $source->basePrice ) ) : null,
			],
			'formattedPrice' => [
				'type'        => 'String',
				'description' => __( 'The price of the product, prefixed by the currency.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->basePrice ) ? $source->basePrice : null,
			],
		];
	}
}
