<?php
/**
 * GraphQL Input Type - EntriesSortingInput
 * Sorting input type for Entries queries.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Types\Enum\SortingInputEnum;

/**
 * Class - EntriesSortingInput
 */
class EntriesSortingInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EntriesSortingInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Sorting input fields for Entries queries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
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
