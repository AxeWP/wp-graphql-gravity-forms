<?php
/**
 * Enum Type - AddressFieldCountryEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

use GF_Fields;
use WPGraphQL\Type\WPEnumType;

/**
 * Class - AddressFieldCountryEnum
 */
class AddressFieldCountryEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressFieldCountryEnum';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Countries supported by Gravity Forms Address Field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		/**
		 * A gravity forms address field.
		 *
		 * @var \GF_Field_Address $field
		 */
		$field     = GF_Fields::get( 'address' );
		$countries = $field->get_default_countries();

		$values = [];

		foreach ( $countries as $code => $name ) {
			$values[ WPEnumType::get_safe_name( $code ) ] = [
				'value'       => $name,
				// translators: Country.
				'description' => sprintf( __( '%s .', 'wp-graphql-gravity-forms' ), $name ),
			];
		}

		return $values;
	}
}
