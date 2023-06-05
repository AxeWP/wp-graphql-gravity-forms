<?php
/**
 * Enum Type - FormButtonTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormButtonTypeEnum
 */
class FormButtonTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormButtonTypeEnum';

	// Individual elements.
	public const TEXT  = 'text';
	public const IMAGE = 'image';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of button to be displayed. Default is TEXT.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'IMAGE' => [
				'description' => __( 'Image button.', 'wp-graphql-gravity-forms' ),
				'value'       => self::IMAGE,
			],
			'TEXT'  => [
				'description' => __( 'Text button (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::TEXT,
			],
		];
	}
}
