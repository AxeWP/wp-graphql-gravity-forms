<?php
/**
 * GraphQL Input Type - FormsSortingInput
 * Sorting input type for Forms queries.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;
use WPGraphQLGravityForms\Types\Enum\SortingInputEnum;

/**
 * Class - FormsSortingInput
 */
class FormsSortingInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'FormsSortingInput';

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
				'description' => __( 'Sorting input fields for Forms queries.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'key'       => [
						'type'        => 'String',
						'description' => __( 'The key of the field to sort by.', 'wp-graphql-gravity-forms' ),
					],
					'direction' => [
						'type'        => SortingInputEnum::$type,
						'description' => __( 'The sorting direction.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
