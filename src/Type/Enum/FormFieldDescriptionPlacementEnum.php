<?php
/**
 * Enum Type - FormFieldDescriptionPlacementEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormFieldDescriptionPlacementEnum
 */
class FormFieldDescriptionPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldDescriptionPlacementEnum';

	// Individual elements.
	public const ABOVE   = 'above';
	public const BELOW   = 'below';
	public const INHERIT = 'inherit';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Determines where the field description is displayed relative to the field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'ABOVE'   => [
				'description' => __( 'The field description is displayed above the field input (i.e. immediately after the field label).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ABOVE,
			],
			'BELOW'   => [
				'description' => __( 'The field description is displayed below the field input.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BELOW,
			],
			'INHERIT' => [
				'description' => __( 'The field description is inherited from the form default settings.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INHERIT,
			],
		];
	}
}
