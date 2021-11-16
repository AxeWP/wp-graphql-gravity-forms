<?php
/**
 * Enum Type - SortingInputEnum
 *
 * @package WPGraphQL\GF\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Types\Enum;

/**
 * Class - SortingInputEnum
 */
class SortingInputEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'SortingInputEnum';

	// Individual elements.
	const ASC  = 'ASC';
	const DESC = 'DESC';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Sorting Direction. Default is DESC', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function get_values() : array {
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
