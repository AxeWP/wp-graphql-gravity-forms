<?php
/**
 * Enum Type - FormSubmitButtonLocationEnum
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since   0.11.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormSubmitButtonLocationEnum
 */
class FormSubmitButtonLocationEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormSubmitButtonLocationEnum';

	// Individual elements.
	public const BOTTOM = 'bottom';
	public const INLINE = 'inline';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Where the submit button should be located.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'BOTTOM' => [
				'description' => __( 'The submit button will be placed in a new row after all fields of the form.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BOTTOM,
			],
			'INLINE' => [
				'description' => __( 'The submit button will be placed on the last row of the form where it will fill the remaining space left by field columns.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INLINE,
			],
		];
	}
}
