<?php
/**
 * GraphQL Object Type - ListInputValue
 * Value for a single input within a List field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - ListInputValue
 */
class ListInputValue implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'ListInputValue';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
			add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Value for a single input within a list field.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'value' => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
