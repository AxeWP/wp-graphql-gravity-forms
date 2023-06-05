<?php
/**
 * Enum Type - FormNotificationToTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

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
				'description' => __( 'Email address.', 'wp-graphql-gravity-forms' ),
				'value'       => self::EMAIL,
			],
			'FIELD'   => [
				'description' => __( 'Form field.', 'wp-graphql-gravity-forms' ),
				'value'       => self::FIELD,
			],
			'ROUTING' => [
				'description' => __( 'Routing using conditional rules.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ROUTING,
			],
			'HIDDEN'  => [
				'description' => __( 'Hidden.', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDDEN,
			],
		];
	}
}
