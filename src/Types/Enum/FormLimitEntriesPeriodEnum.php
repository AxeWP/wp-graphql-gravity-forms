<?php
/**
 * Enum Type - FormLimitEntriesPeriodEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - FormLimitEntriesPeriodEnum
 */
class FormLimitEntriesPeriodEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FormLimitEntriesPeriodEnum';

	// Individual elements.
	const DAY   = 'day';
	const WEEK  = 'week';
	const MONTH = 'month';
	const YEAR  = 'year';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'When limitEntries is set to 1, this property specifies the time period during which submissions are allowed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'DAY'   => [
				'description' => __( 'Limit entries by "day".', 'wp-graphql-gravity-forms' ),
				'value'       => self::DAY,
			],
			'WEEK'  => [
				'description' => __( 'Limit entries by "week".', 'wp-graphql-gravity-forms' ),
				'value'       => self::WEEK,
			],
			'MONTH' => [
				'description' => __( 'Limit entries by "month".', 'wp-graphql-gravity-forms' ),
				'value'       => self::MONTH,
			],
			'YEAR'  => [
				'description' => __( 'Limit entries by "year".', 'wp-graphql-gravity-forms' ),
				'value'       => self::YEAR,
			],
		];
	}
}
