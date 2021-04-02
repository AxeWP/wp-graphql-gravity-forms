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
use WPGraphQLGravityForms\Types\Enum\RuleOperatorEnum;

/**
 * Class - ConditionalLogicRule
 */
class ConditionalLogicRule implements Hookable, Type {
	const TYPE = 'ConditionalLogicRule';

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
			self::TYPE,
			[
				'description' => __( 'Gravity Forms conditional logic rule.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'fieldId'  => [
						'type'        => 'Float',
						'description' => __( 'Target field Id. Field that will have it’s value compared with the value property to determine if this rule is a match.', 'wp-graphql-gravity-forms' ),
					],
					'operator' => [
						'type'        => RuleOperatorEnum::$type,
						'description' => __( 'Operator to be used when evaluating this rule.', 'wp-graphql-gravity-forms' ),
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
