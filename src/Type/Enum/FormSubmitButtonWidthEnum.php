<?php
/**
 * Enum Type - FormSubmitButtonWidthEnum
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since   0.11.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormSubmitButtonWidthEnum
 */
class FormSubmitButtonWidthEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormSubmitButtonWidthEnum';

	// Individual elements.
	public const AUTO = 'auto';
	public const FULL = 'full';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Submit button width.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'AUTO' => [
				'description' => __( 'The width is set to match that of the button text.', 'wp-graphql-gravity-forms' ),
				'value'       => self::AUTO,
			],
			'FULL' => [
				'description' => __( 'The width is set to fill 100% of the container.', 'wp-graphql-gravity-forms' ),
				'value'       => self::FULL,
			],
		];
	}
}
