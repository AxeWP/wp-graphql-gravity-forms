<?php
/**
 * Enum Type - FormFieldVisibilityEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormFieldVisibilityEnum
 */
class FormFieldVisibilityEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldVisibilityEnum';

	// Individual elements.
	public const VISIBLE        = 'visible';
	public const HIDDEN         = 'hidden';
	public const ADMINISTRATIVE = 'administrative';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Field visibility.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'VISIBLE'        => [
				'description' => __( 'The field is "visible".', 'wp-graphql-gravity-forms' ),
				'value'       => self::VISIBLE,
			],
			'HIDDEN'         => [
				'description' => __( 'The field is "hidden".', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDDEN,
			],
			'ADMINISTRATIVE' => [
				'description' => __( 'The field is for "administrative" use.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ADMINISTRATIVE,
			],
		];
	}
}
