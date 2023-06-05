<?php
/**
 * Enum Type - CurrencyEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.2
 */

namespace WPGraphQL\GF\Type\Enum;

use RGCurrency;
use WPGraphQL\Type\WPEnumType;

/**
 * Class - CurrencyEnum
 */
class CurrencyEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfCurrencyEnum';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Currencies supported by Gravity Forms.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		$currencies = RGCurrency::get_currencies();

		$values = [];

		foreach ( $currencies as $code => $currency ) {
			$values[ WPEnumType::get_safe_name( $code ) ] = [
				'value'       => $currency['code'],
				// translators: Currency Name.
				'description' => sprintf( __( '%s .', 'wp-graphql-gravity-forms' ), $currency['name'] ),
			];
		}

		return $values;
	}
}
