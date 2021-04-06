<?php
/**
 * Enum Type - TimeFieldFormatEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - TimeFieldFormatEnum
 */
class TimeFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'TimeFieldFormatEnum';

	// Individual elements.
	const H12 = '12';
	const H24 = '24';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'How the time is displayed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'H12' => [
				'description' => __( '12-hour time format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::H12,
			],
			'H24' => [
				'description' => __( '24-hour time format', 'wp-graphql-gravity-forms' ),
				'value'       => self::H24,
			],
		];
	}
}
