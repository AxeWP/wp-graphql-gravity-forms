<?php
/**
 * Enum Type - FieldFiltersModeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - FieldFiltersModeEnum
 */
class FieldFiltersModeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FieldFiltersModeEnum';

	// Individual elements.
	const ALL = 'all';
	const ANY = 'any';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Whether to filter by ALL or ANY of the field filters. Default is ALL.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'ALL' => [
				'description' => __( 'All field filters (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ALL,
			],
			'ANY' => [
				'description' => __( 'Any field filters', 'wp-graphql-gravity-forms' ),
				'value'       => self::ANY,
			],
		];
	}
}
