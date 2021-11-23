<?php
/**
 * Enum Type - ChainedSelectsAlignmentEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - ChainedSelectsAlignmentEnum
 */
class ChainedSelectsAlignmentEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectsAlignmentEnum';

	// Individual elements.
	const HORIZONTAL = 'horizontal';
	const VERTICAL   = 'vertical';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values() : array {
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
