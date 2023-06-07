<?php
/**
 * GraphQL Input Type - EntriesDateFiltersInput
 * Date Filters input type for Entries queries.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - EntriesDateFiltersInput
 */
class EntriesDateFiltersInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntriesDateFiltersInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Date Filters input fields for Entries queries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'startDate' => [
				'type'        => 'String',
				'description' => __( 'Start date in Y-m-d H:i:s format.', 'wp-graphql-gravity-forms' ),
			],
			'endDate'   => [
				'type'        => 'String',
				'description' => __( 'End date in Y-m-d H:i:s format.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
