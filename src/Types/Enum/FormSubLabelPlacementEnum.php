<?php
/**
 * Enum Type - FormSubLabelPlacementEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - FormSubLabelPlacementEnum
 */
class FormSubLabelPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FormSubLabelPlacementEnum';

	// Individual elements.
	const ABOVE = 'above';
	const BELOW = 'below';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Determines how sub-labels are aligned.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'ABOVE' => [
				'description' => __( 'The sub-label is displayed above the sub-field input (i.e. immediately after the field label)', 'wp-graphql-gravity-forms' ),
				'value'       => self::ABOVE,
			],
			'BELOW' => [
				'description' => __( 'The sub-label is displayed below the sub-field input.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BELOW,
			],
		];
	}
}
