<?php
/**
 * GraphQL Object Type - ConditionalLogic
 *
 * @see https://docs.gravityforms.com/conditional-logic/
 *
 * @package WPGraphQL\GF\Type\WPObject\ConditionalLogic
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\ConditionalLogic;

use WPGraphQL\GF\Type\Enum\ConditionalLogicActionTypeEnum;
use WPGraphQL\GF\Type\Enum\ConditionalLogicLogicTypeEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - ConditionalLogic
 */
class ConditionalLogic extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ConditionalLogic';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms conditional logic.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'actionType' => [
				'type'        => ConditionalLogicActionTypeEnum::$type,
				'description' => __( 'The type of action the conditional logic will perform.', 'wp-graphql-gravity-forms' ),
			],
			'logicType'  => [
				'type'        => ConditionalLogicLogicTypeEnum::$type,
				'description' => __( 'Determines how to the rules should be evaluated.', 'wp-graphql-gravity-forms' ),
			],
			'rules'      => [
				'type'        => [ 'list_of' => ConditionalLogicRule::$type ],
				'description' => __( 'Conditional logic rules.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
