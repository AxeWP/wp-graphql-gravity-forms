<?php
/**
 * Enum Type - FormLabelPlacementEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - FormLabelPlacementEnum
 */
class FormLabelPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FormLabelPlacementEnum';

	// Individual elements.
	const TOP   = 'top_label';
	const LEFT  = 'left_label';
	const RIGHT = 'right_label';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Determines where the field labels should be placed in relation to the field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
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
