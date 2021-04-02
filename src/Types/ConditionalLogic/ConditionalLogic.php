<?php
/**
 * GraphQL Object Type - ConditionalLogic
 *
 * @see https://docs.gravityforms.com/conditional-logic/
 *
 * @package WPGraphQLGravityForms\Types\ConditionalLogic
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\ConditionalLogic;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Types\Enum\ConditionalLogicActionTypeEnum;
use WPGraphQLGravityForms\Types\Enum\ConditionalLogicLogicTypeEnum;

/**
 * Class - ConditionalLogic
 */
class ConditionalLogic implements Hookable, Type {
	const TYPE = 'ConditionalLogic';

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
				'description' => __( 'Gravity Forms conditional logic.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'actionType' => [
						'type'        => ConditionalLogicActionTypeEnum::$type,
						'description' => __( 'The type of action the conditional logic will perform.', 'wp-graphql-gravity-forms' ),
					],
					'logicType'  => [
						'type'        => ConditionalLogicLogicTypeEnum::$type,
						'description' => __( 'Determines how to the rules should be evaluated.', 'wp-graphql-gravity-forms' ),
					],
					'rules'      => [
						'type'        => [ 'list_of' => ConditionalLogicRule::TYPE ],
						'description' => __( 'Conditional logic rules.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
