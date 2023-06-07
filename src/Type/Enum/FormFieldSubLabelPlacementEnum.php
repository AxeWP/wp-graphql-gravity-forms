<?php
/**
 * Enum Type - FormFieldSubLabelPlacementEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormFieldSubLabelPlacementEnum
 */
class FormFieldSubLabelPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldSubLabelPlacementEnum';

	// Individual elements.
	public const ABOVE   = 'above';
	public const BELOW   = 'below';
	public const INHERIT = 'inherit';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Determines how sub-labels are aligned.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'ABOVE'   => [
				'description' => __( 'The sub-label is displayed above the sub-field input (i.e. immediately after the field label).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ABOVE,
			],
			'BELOW'   => [
				'description' => __( 'The sub-label is displayed below the sub-field input.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BELOW,
			],
			'INHERIT' => [
				'description' => __( 'Field label is inherited from the form defaults.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INHERIT,
			],
		];
	}
}
