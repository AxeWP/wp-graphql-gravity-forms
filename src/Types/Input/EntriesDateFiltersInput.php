<?php
/**
 * GraphQL Input Type - EntriesDateFiltersInput
 * Date Filters input type for Entries queries.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

/**
 * Class - EntriesDateFiltersInput
 */
class EntriesDateFiltersInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EntriesDateFiltersInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Date Filters input fields for Entries queries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_properties() : array {
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
