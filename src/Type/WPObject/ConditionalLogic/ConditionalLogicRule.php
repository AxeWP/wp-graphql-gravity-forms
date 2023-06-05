<?php
/**
 * GraphQL Object Type - Conditional Logic rule property
 *
 * @see https://docs.gravityforms.com/conditional-logic/#rule-properties
 *
 * @package WPGraphQL\GF\Type\WPObject\ConditionalLogic
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\ConditionalLogic;

use WPGraphQL\GF\Type\Enum\FormRuleOperatorEnum;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - ConditionalLogicRule
 */
class ConditionalLogicRule extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ConditionalLogicRule';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms conditional logic rule.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'fieldId'  => [
				'type'        => 'Float',
				'description' => __( 'Target field Id. Field that will have it’s value compared with the value property to determine if this rule is a match.', 'wp-graphql-gravity-forms' ),
			],
			'operator' => [
				'type'        => FormRuleOperatorEnum::$type,
				'description' => __( 'Operator to be used when evaluating this rule.', 'wp-graphql-gravity-forms' ),
			],
			'value'    => [
				'type'        => 'String',
				'description' => __( 'The value to compare with field specified by fieldId.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
