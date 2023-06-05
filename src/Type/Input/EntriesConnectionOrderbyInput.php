<?php
/**
 * GraphQL Input Type - EntriesConnectionOrderbyInput
 * Sorting input type for Entries queries.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - EntriesConnectionOrderbyInput
 */
class EntriesConnectionOrderbyInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntriesConnectionOrderbyInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Options for ordering the connection.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'field'     => [
				'type'        => 'String',
				'description' => __( 'The field name used to sort the results.', 'wp-graphql-gravity-forms' ),
			],
			'order'     => [
				'type'        => 'OrderEnum',
				'description' => __( 'The cardinality of the order of the connection.', 'wp-graphql-gravity-forms' ),
			],
			'isNumeric' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the sorting field\'s values are numeric.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
