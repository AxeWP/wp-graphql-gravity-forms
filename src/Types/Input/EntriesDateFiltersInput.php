<?php
/**
 * GraphQL Input Type - EntriesDateFiltersInput
 * Date Filters input type for Entries queries.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - EntriesDateFiltersInput
 */
class EntriesDateFiltersInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'EntriesDateFiltersInput';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
	}

	/**
	 * Register input type to GraphQL schema.
	 */
	public function register_input_type() : void {
		register_graphql_input_type(
			self::TYPE,
			[
				'description' => __( 'Date Filters input fields for Entries queries.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'startDate' => [
						'type'        => 'String',
						'description' => __( 'Start date in Y-m-d H:i:s format.', 'wp-graphql-gravity-forms' ),
					],
					'endDate'   => [
						'type'        => 'String',
						'description' => __( 'End date in Y-m-d H:i:s format.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
