<?php
/**
 * GraphQL Input Type - ListInput
 * Input fields for a single List field item.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 * @since   0.3.0 Deprecate `values` in favor of `rowValues`.
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - ListInput
 */
class ListInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'ListInput';

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
				'description' => __( 'Input fields for a single List field item.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'values'    => [
						'type'              => [ 'list_of' => 'String' ],
						'description'       => __( 'Input value. Deprecated - please use `rowValues` instead.', 'wp-graphql-gravity-forms' ),
						'deprecationReason' => __( 'Please use `rowValues` instead.', 'wp-graphql-gravity-forms' ),
					],
					'rowValues' => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'Input values for the specific listField row.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
