<?php
/**
 * Enum Type - SignatureBorderWidthEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - SignatureBorderWidthEnum
 */
class SignatureBorderWidthEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SignatureBorderWidthEnum';

	// Individual elements.
	const NONE   = '0';
	const SMALL  = '1';
	const MEDIUM = '2';
	const LARGE  = '3';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Width of the border around the signature area.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'NONE'   => [
				'description' => __( 'No border width.', 'wp-graphql-gravity-forms' ),
				'value'       => self::NONE,
			],
			'SMALL'  => [
				'description' => __( 'A small border width', 'wp-graphql-gravity-forms' ),
				'value'       => self::SMALL,
			],
			'MEDIUM' => [
				'description' => __( 'A medium border width', 'wp-graphql-gravity-forms' ),
				'value'       => self::MEDIUM,
			],
			'LARGE'  => [
				'description' => __( 'A large border width', 'wp-graphql-gravity-forms' ),
				'value'       => self::LARGE,
			],
		];
	}
}
