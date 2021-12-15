<?php
/**
 * GraphQL Input Type - FormsSortingInput
 * Sorting input type for Forms queries.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.6.0
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Type\Enum\SortingInputEnum;

/**
 * Class - FormsSortingInput
 */
class FormsSortingInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormsSortingInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Sorting input fields for Forms queries.', 'wp-graphql-gravity-forms' );
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
				'type'        => 'OrderEnum',
				'description' => __( 'The sorting direction.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
