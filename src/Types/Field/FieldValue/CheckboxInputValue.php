<?php
/**
 * GraphQL Object Type - CheckboxInputValue
 * Value for a single input within a checkbox field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - CheckboxInputValue
 */
class CheckboxInputValue implements Hookable, Type {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxInputValue';

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
				'description' => __( 'Value for a single input within a checkbox field.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'inputId' => [
						'type'        => 'Float',
						'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
					],
					'value'   => [
						'type'        => 'String',
						'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
