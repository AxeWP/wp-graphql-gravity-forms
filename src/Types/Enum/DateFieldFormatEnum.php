<?php
/**
 * Enum Type - DateFieldFormatEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - DateFieldFormatEnum
 */
class DateFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'DateFieldFormatEnum';

	// Individual elements.
	const MDY = 'mdy';
	const DMY = 'dmy';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'How the DateField date is displayed', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'MDY' => [
				'description' => __( 'MDY format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::MDY,
			],
			'DMY' => [
				'description' => __( 'DMY format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DMY,
			],
		];
	}
}
