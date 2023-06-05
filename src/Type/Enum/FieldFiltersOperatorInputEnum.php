<?php
/**
 * Enum Type - FieldFiltersOperatorInputEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FieldFiltersOperatorEnum
 */
class FieldFiltersOperatorInputEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FieldFiltersOperatorEnum';

	// Individual elements.
	public const CONTAINS = 'contains';
	public const IN       = 'in';
	public const IS       = 'is';
	public const IS_NOT   = 'is not';
	public const LIKE     = 'like';
	public const NOT_IN   = 'not in';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The operator to use for filtering.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'CONTAINS' => [
				'description' => __( 'Find field values that contain the passed value. Only one value may be passed when using this operator. SQL Equivalent: `LIKE %value%`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CONTAINS,
			],
			'IN'       => [
				'description' => __( 'Default. Find field values that are equal to one of the values in the passed array. Default.', 'wp-graphql-gravity-forms' ),
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
