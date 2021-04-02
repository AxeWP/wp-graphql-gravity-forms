<?php
/**
 * Enum Type - FieldFiltersOperatorInputEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - FieldFiltersOperatorInputEnum
 */
class FieldFiltersOperatorInputEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FieldFiltersOperatorInputEnum';

	// Individual elements.
	const IN           = 'in';
	const NOT_IN       = 'not in';
	const CONTAINS     = 'contains';
	const GREATER_THAN = '>';
	const LESS_THAN    = '<';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'The operator to use for filtering.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'IN'           => [
				'description' => __( 'Find field values that match those in the values array (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::IN,
			],
			'NOT_IN'       => [
				'description' => __( 'Find field values that do NOT match those in the values array.', 'wp-graphql-gravity-forms' ),
				'value'       => self::NOT_IN,
			],
			'CONTAINS'     => [
				'description' => __( 'Find field values that contain the value in the values array. Only the first value in the values array will be used; any others will be disregarded.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CONTAINS,
			],
			'GREATER_THAN' => [
				'description' => __( 'Find field values that are greater than the value in the values array. Only the first value in the values array will be used; any others will be disregarded.', 'wp-graphql-gravity-forms' ),
				'value'       => self::GREATER_THAN,
			],
			'LESS_THAN'    => [
				'description' => __( 'Find field values that are less than the value in the values array. Only the first value in the values array will be used; any others will be disregarded.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LESS_THAN,
			],
		];
	}
}
