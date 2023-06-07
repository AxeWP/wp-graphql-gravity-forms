<?php
/**
 * Enum Type - PasswordFieldMinStrengthEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - PasswordFieldMinStrengthEnum
 */
class PasswordFieldMinStrengthEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PasswordFieldMinStrengthEnum';

	// Individual elements.
	public const SHORT  = 'short';
	public const BAD    = 'bad';
	public const GOOD   = 'good';
	public const STRONG = 'strong';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Indicates how strong the password should be.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
