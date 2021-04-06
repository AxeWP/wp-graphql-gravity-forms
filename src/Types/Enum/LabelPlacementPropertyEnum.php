<?php
/**
 * Enum Type - LabelPlacementPropertyEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - LabelPlacementPropertyEnum
 */
class LabelPlacementPropertyEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'LabelPlacementPropertyEnum';

	// Individual elements.
	const TOP     = 'top_label';
	const LEFT    = 'left_label';
	const RIGHT   = 'right_label';
	const INHERIT = '';
	const HIDDEN  = 'hidden_label';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'The field label position. Empty when using the form defaults or a value of "hidden_label".', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'TOP'     => [
				'description' => __( 'Field label is displayed on top of the fields.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TOP,
			],
			'LEFT'    => [
				'description' => __( 'Field label is displayed beside the fields and aligned to the left.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LEFT,
			],
			'RIGHT'   => [
				'description' => __( 'Field label is displayed beside the fields and aligned to the right.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RIGHT,
			],
			'INHERIT' => [
				'description' => __( 'Field label is inherited from the form defaults.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INHERIT,
			],
			'HIDDEN'  => [
				'description' => __( 'Field label is hidden.', 'wp-graphql-gravity-forms' ),
				'value'       => self::HIDDEN,
			],
		];
	}
}
