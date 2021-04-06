<?php
/**
 * Enum Type - ChainedSelectsAlignmentEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - ChainedSelectsAlignmentEnum
 */
class ChainedSelectsAlignmentEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ChainedSelectsAlignmentEnum';

	// Individual elements.
	const HORIZONTAL = 'horizontal';
	const VERTICAL   = 'vertical';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'HORIZONTAL' => [
				'description' => __( 'Horizontal alignment (in a row).', 'wp-graphql-gravity-forms' ),
				'value'       => self::HORIZONTAL,
			],
			'VERTICAL'   => [
				'description' => __( 'Vertical alignment (in a column).', 'wp-graphql-gravity-forms' ),
				'value'       => self::VERTICAL,
			],
		];
	}
}
