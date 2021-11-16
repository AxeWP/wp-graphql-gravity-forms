<?php
/**
 * Enum Type - SizePropertyEnum
 *
 * @package WPGraphQL\GF\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Types\Enum;

/**
 * Class - SizePropertyEnum
 */
class SizePropertyEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SizePropertyEnum';

	// Individual elements.
	const SMALL  = 'small';
	const MEDIUM = 'medium';
	const LARGE  = 'large';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'The size of the field when displayed on the page.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function get_values() : array {
		return [
			'SMALL'  => [
				'description' => __( 'Small field size.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SMALL,
			],
			'MEDIUM' => [
				'description' => __( 'Medium field size.', 'wp-graphql-gravity-forms' ),
				'value'       => self::MEDIUM,
			],
			'LARGE'  => [
				'description' => __( 'Large field size.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LARGE,
			],
		];
	}
}
