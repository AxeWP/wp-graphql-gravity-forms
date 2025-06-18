<?php
/**
 * Enum Type - FormNotificationToTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormNotificationToTypeEnum
 */
class FormNotificationToTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormNotificationToTypeEnum';

	// Individual elements.
	public const EMAIL   = 'email';
	public const FIELD   = 'field';
	public const ROUTING = 'routing';
	public const HIDDEN  = 'hidden';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'What to use for the notification "to".', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'EMAIL'   => [
				'description' => static fn () => __( 'Email address.', 'wp-graphql-gravity-forms' ),
				'value'       => self::EMAIL,
			],
			'FIELD'   => [
				'description' => static fn () => __( 'Form field.', 'wp-graphql-gravity-forms' ),
				'value'       => self::FIELD,
			],
			'ROUTING' => [
				'description' => static fn () => __( 'Routing using conditional rules.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ROUTING,
			],
			'HIDDEN'  => [
				'description' => static fn () => __( 'Hidden.', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDDEN,
			],
		];
	}
}
