<?php
/**
 * GraphQL Object Type - Conditional Logic rule property
 *
 * @see https://docs.gravityforms.com/conditional-logic/#rule-properties
 *
 * @package WPGraphQL\GF\Types\ConditionalLogic
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\ConditionalLogic;

use WPGraphQL\GF\Types\AbstractObject;
use WPGraphQL\GF\Types\Enum\RuleOperatorEnum;

/**
 * Class - ConditionalLogicRule
 */
class ConditionalLogicRule extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ConditionalLogicRule';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms conditional logic rule.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
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
		];
	}
}
