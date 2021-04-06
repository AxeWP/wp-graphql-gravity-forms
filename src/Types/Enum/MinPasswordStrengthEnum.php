<?php
/**
 * Enum Type - MinPasswordStrengthEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - MinPasswordStrengthEnum
 */
class MinPasswordStrengthEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'MinPasswordStrengthEnum';

	// Individual elements.
	const SHORT  = 'short';
	const BAD    = 'bad';
	const GOOD   = 'good';
	const STRONG = 'strong';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Indicates how strong the password should be.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'SHORT'  => [
				'description' => __( 'The password strength must be "short" or better.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SHORT,
			],
			'BAD'    => [
				'description' => __( 'The password strength must be "bad" or better.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BAD,
			],
			'GOOD'   => [
				'description' => __( 'The password strength must be "good" or better.', 'wp-graphql-gravity-forms' ),
				'value'       => self::GOOD,
			],
			'STRONG' => [
				'description' => __( 'The password strength must be "strong".', 'wp-graphql-gravity-forms' ),
				'value'       => self::STRONG,
			],
		];
	}
}
