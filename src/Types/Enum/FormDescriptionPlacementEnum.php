<?php
/**
 * Enum Type - FormDescriptionPlacementEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - FormDescriptionPlacementEnum
 */
class FormDescriptionPlacementEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FormDescriptionPlacementEnum';

	// Individual elements.
	const ABOVE = 'above';
	const BELOW = 'below';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Determines where the field description is displayed relative to the field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'ABOVE' => [
				'description' => __( 'The field description is displayed above the field input (i.e. immediately after the field label)', 'wp-graphql-gravity-forms' ),
				'value'       => self::ABOVE,
			],
			'BELOW' => [
				'description' => __( 'The field description is displayed below the field input.', 'wp-graphql-gravity-forms' ),
				'value'       => self::BELOW,
			],
		];
	}
}
