<?php
/**
 * GraphQL Object Type - ConditionalLogic
 *
 * @see https://docs.gravityforms.com/conditional-logic/
 *
 * @package WPGraphQL\GF\Types\ConditionalLogic
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\ConditionalLogic;

use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Enum\ConditionalLogicActionTypeEnum;
use WPGraphQL\GF\Types\Enum\ConditionalLogicLogicTypeEnum;

/**
 * Class - ConditionalLogic
 */
class ConditionalLogic extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ConditionalLogic';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms conditional logic.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
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
