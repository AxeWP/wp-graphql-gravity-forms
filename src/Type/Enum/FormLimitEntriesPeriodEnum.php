<?php
/**
 * Enum Type - FormLimitEntriesPeriodEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormLimitEntriesPeriodEnum
 */
class FormLimitEntriesPeriodEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormLimitEntriesPeriodEnum';

	// Individual elements.
	public const DAY   = 'day';
	public const WEEK  = 'week';
	public const MONTH = 'month';
	public const YEAR  = 'year';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'When limitEntries is set to 1, this property specifies the time period during which submissions are allowed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
