<?php
/**
 * Enum Type - FieldFiltersOperatorInputEnum
 *
 * @package WPGraphQL\GF\Types\Enum,
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Enum;

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
	const CONTAINS = 'contains';
	const IN       = 'in';
	const IS       = 'is';
	const IS_NOT   = 'is not';
	const LIKE     = 'like';
	const NOT_IN   = 'not in';

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
	public function get_values() : array {
		return [
			'CONTAINS' => [
				'description' => __( 'Find field values that contain the passed value. Only one value may be passed when using this operator. SQL Equivalent: `LIKE %value%`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CONTAINS,
			],
			'IN'       => [
				'description' => __( 'Default. Find field values that are equal to one of the values in the passed array. Default', 'wp-graphql-gravity-forms' ),
				'value'       => self::IN,
			],
			'IS'       => [
				'description' => __( 'Find field values that are an exact match for the passed value. Only one value may be passed when using this operator. SQL Equivalent: `=`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::IS,
			],
			'IS_NOT'   => [
				'description' => __( 'Find field values that are NOT an exact match for the passed value. Only one value may be passed when using this operator. SQL Equivalent: `NOT`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::IS_NOT,
			],
			'LIKE'     => [
				'description' => __( 'Find field values that are an exact match for the passed value. SQL wildcards are supported. Only one value may be passed when using this operator. SQL Equivalent: `LIKE`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LIKE,
			],
			'NOT_IN'   => [
				'description' => __( 'Find field values that do NOT match those in the values array.', 'wp-graphql-gravity-forms' ),
				'value'       => self::NOT_IN,
			],
		];
	}
}
