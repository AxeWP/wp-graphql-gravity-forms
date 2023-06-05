<?php
/**
 * Enum Type - FormFieldLabelPlacementEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormFieldLabelPlacementEnum
 */
class FormFieldLabelPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldLabelPlacementEnum';

	// Individual elements.
	public const TOP     = 'top_label';
	public const LEFT    = 'left_label';
	public const RIGHT   = 'right_label';
	public const INHERIT = 'inherit';
	public const HIDDEN  = 'hidden_label';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The field label position. Empty when using the form defaults or a value of "hidden_label".', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'HIDDEN'  => [
				'description' => __( 'Field label is hidden.', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDDEN,
			],
			'INHERIT' => [
				'description' => __( 'Field label is inherited from the form defaults.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INHERIT,
			],
			'LEFT'    => [
				'description' => __( 'Field label is displayed beside the fields and aligned to the left.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LEFT,
			],
			'RIGHT'   => [
				'description' => __( 'Field label is displayed beside the fields and aligned to the right.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RIGHT,
			],
			'TOP'     => [
				'description' => __( 'Field label is displayed on top of the fields.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TOP,
			],
		];
	}
}
