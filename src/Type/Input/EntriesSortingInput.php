<?php
/**
 * GraphQL Input Type - EntriesSortingInput
 * Sorting input type for Entries queries.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\SortingInputEnum;

/**
 * Class - EntriesSortingInput
 */
class EntriesSortingInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntriesSortingInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Sorting input fields for Entries queries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'key'       => [
				'type'        => 'String',
				'description' => __( 'The key of the field to sort by.', 'wp-graphql-gravity-forms' ),
			],
			'direction' => [
				'type'        => SortingInputEnum::$type,
				'description' => __( 'The sorting direction.', 'wp-graphql-gravity-forms' ),
			],
			'isNumeric' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the sorting field\'s values are numeric.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
