<?php
/**
 * Enum Type - SortingInputEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - SortingInputEnum
 */
class SortingInputEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SortingInputEnum';

	// Individual elements.
	const ASC  = 'ASC';
	const DESC = 'DESC';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Sorting Direction. Default is DESC', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values() : array {
		return [
			self::ASC  => [
				'description' => __( 'Sort by ascending.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ASC,
			],
			self::DESC => [
				'description' => __( 'Sort by descending (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::DESC,
			],
		];
	}
}
