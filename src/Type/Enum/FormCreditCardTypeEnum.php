<?php
/**
 * Enum Type - FormCreditCardTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

use GFCommon;
use WPGraphQL\Type\WPEnumType;

/**
 * Class - FormCreditCardTypeEnum
 */
class FormCreditCardTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFormCreditCardTypeEnum';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of Credit Card supported by Gravity Forms.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		$cards = GFCommon::get_card_types();

		$values = [];

		foreach ( $cards as $type ) {
			$values[ WPEnumType::get_safe_name( $type['slug'] ) ] = [
				'value'       => $type['slug'],
				// translators: Credit card name.
				'description' => sprintf( __( ' A %s type credit card.', 'wp-graphql-gravity-forms' ), $type['name'] ),
			];
		}

		return $values;
	}
}
