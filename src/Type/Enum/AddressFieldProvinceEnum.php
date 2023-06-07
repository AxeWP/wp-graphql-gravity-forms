<?php
/**
 * Enum Type - AddressFieldProvinceEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\Enum;

use GF_Fields;
use WPGraphQL\Type\WPEnumType;

/**
 * Class - AddressFieldProvinceEnum
 */
class AddressFieldProvinceEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressFieldProvinceEnum';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Canadian Provinces supported by Gravity Forms Address Field.', 'wp-graphql-gravity-forms' );
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
		$provinces = $field->get_canadian_provinces();

		$values = [];

		foreach ( $provinces as $province ) {
			$values[ WPEnumType::get_safe_name( $province ) ] = [
				'value'       => $province,
				// translators: Province.
				'description' => sprintf( __( '%s .', 'wp-graphql-gravity-forms' ), $province ),
			];
		}

		return $values;
	}
}
