<?php
/**
 * GraphQL Object Type - ListInputValue
 * Value for a single input within a List field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 * @since   0.3.0 Deprecate `value` in favor of `values`.
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
	 *
	 * @var string
	 */
	public static $type = 'ListInputValue';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
			add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() : void {
		register_graphql_object_type(
			self::$type,
			[
				'description' => __( 'Value for a single input within a list field.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'value'  => [
						'type'              => [ 'list_of' => 'String' ],
						'description'       => __( 'Input value', 'wp-graphql-gravity-forms' ),
						'deprecationReason' => __( 'Please use `values` instead.', 'wp-graphql-gravity-forms' ),
					],
					'values' => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'Input values', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
