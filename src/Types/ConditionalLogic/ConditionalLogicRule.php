<?php
/**
 * GraphQL Object Type - Conditional Logic rule property
 *
 * @see https://docs.gravityforms.com/conditional-logic/#rule-properties
 *
 * @package WPGraphQLGravityForms\Types\ConditionalLogic
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\ConditionalLogic;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;

/**
 * Class - ConditionalLogicRule
 */
class ConditionalLogicRule implements Hookable, Type {
	const TYPE = 'ConditionalLogicRule';

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
				'description' => __( 'Gravity Forms conditional logic rule.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'fieldId'  => [
						'type'        => 'Float',
						'description' => __( 'Target field Id. Field that will have it’s value compared with the value property to determine if this rule is a match.', 'wp-graphql-gravity-forms' ),
					],
					// TODO: convert to enum.
					'operator' => [
						'type'        => 'String',
						'description' => __( 'Operator to be used when evaluating this rule. Possible values: is, isnot, >, <, contains, starts_with, or ends_with.', 'wp-graphql-gravity-forms' ),
					],
					'value'    => [
						'type'        => 'String',
						'description' => __( 'The value to compare with field specified by fieldId.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
