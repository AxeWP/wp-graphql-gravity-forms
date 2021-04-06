<?php
/**
 * Enum Type - AddressTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - AddressTypeEnum
 */
class AddressTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'AddressTypeEnum';

	// Individual elements.
	const INTERNATIONAL = 'international';
	const US            = 'us';
	const CANADIAN      = 'canadian';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Determines the type of address to be displayed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'INTERNATIONAL' => [
				'description' => __( 'International address type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INTERNATIONAL,
			],
			'US'            => [
				'description' => __( 'United States address type.', 'wp-graphql-gravity-forms' ),
				'value'       => self::US,
			],
			'CANADA'        => [
				'description' => __( 'Canada address type', 'wp-graphql-gravity-forms' ),
				'value'       => self::CANADIAN,
			],
		];
	}
}
