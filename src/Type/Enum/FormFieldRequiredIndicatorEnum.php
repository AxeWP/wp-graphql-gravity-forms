<?php
/**
 * Enum Type - FormFieldRequiredIndicatorEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.6.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormFieldRequiredIndicatorEnum
 */
class FormFieldRequiredIndicatorEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldRequiredIndicatorEnum';

	// Individual elements.
	public const ASTERISK = 'asterisk';
	public const CUSTOM   = 'custom';
	public const TEXT     = 'text';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of indicator to use when field is required.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'ASTERISK' => [
				'description' => __( 'Asterisk (*) indicator.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ASTERISK,
			],
			'CUSTOM'   => [
				'description' => __( 'Custom indicator.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CUSTOM,
			],
			'TEXT'     => [
				'description' => __( 'Text (Required) indicator (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::TEXT,
			],
		];
	}
}
