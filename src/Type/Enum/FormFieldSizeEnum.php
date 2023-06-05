<?php
/**
 * Enum Type - FormFieldSizeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormFieldSizeEnum
 */
class FormFieldSizeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldSizeEnum';

	// Individual elements.
	public const SMALL  = 'small';
	public const MEDIUM = 'medium';
	public const LARGE  = 'large';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The size of the field when displayed on the page.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
