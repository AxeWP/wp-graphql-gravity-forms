<?php
/**
 * Enum Type - FormLabelPlacementEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormLabelPlacementEnum
 */
class FormLabelPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormLabelPlacementEnum';

	// Individual elements.
	public const TOP   = 'top_label';
	public const LEFT  = 'left_label';
	public const RIGHT = 'right_label';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Determines where the field labels should be placed in relation to the field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'TOP'   => [
				'description' => __( 'Field labels are displayed on top of the fields.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TOP,
			],
			'LEFT'  => [
				'description' => __( 'Field labels are displayed beside the fields and aligned to the left.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LEFT,
			],
			'RIGHT' => [
				'description' => __( 'Field labels are displayed beside the fields and aligned to the right.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RIGHT,
			],
		];
	}
}
