<?php
/**
 * Enum Type - ChainedSelectFieldAlignmentEnum
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects\Type\Enum
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\Enum;

use WPGraphQL\GF\Type\Enum\AbstractEnum;

/**
 * Class - ChainedSelectFieldAlignmentEnum
 */
class ChainedSelectFieldAlignmentEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectFieldAlignmentEnum';

	// Individual elements.
	public const HORIZONTAL = 'horizontal';
	public const VERTICAL   = 'vertical';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Alignment of the dropdown fields.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
