<?php
/**
 * Enum Type - AddressFieldStateEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\Enum;

use GF_Fields;
use WPGraphQL\Type\WPEnumType;

/**
 * Class - AddressFieldStateEnum
 */
class AddressFieldStateEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'AddressFieldStateEnum';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'US States supported by Gravity Forms Address Field.', 'wp-graphql-gravity-forms' );
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
		$field  = GF_Fields::get( 'address' );
		$states = $field->get_us_states();

		$values = [];

		foreach ( $states as $state ) {
			$values[ WPEnumType::get_safe_name( $state ) ] = [
				'value'       => $state,
				// translators: State.
				'description' => sprintf( __( '%s .', 'wp-graphql-gravity-forms' ), $state ),
			];
		}

		return $values;
	}
}
