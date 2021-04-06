<?php
/**
 * Enum Type - RuleOperatorEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - RuleOperatorEnum
 */
class RuleOperatorEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'RuleOperatorEnum';

	// Individual elements.
	const IS           = 'is';
	const IS_NOT       = 'isnot';
	const CONTAINS     = 'contains';
	const GREATER_THAN = '>';
	const LESS_THAN    = '<';
	const STARTS_WITH  = 'starts_with';
	const ENDS_WITH    = 'ends_with';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Operator to be used when evaluating logic rules.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'IS'           => [
				'description' => __( 'Evaluates values that match the comparison value.', 'wp-graphql-gravity-forms' ),
				'value'       => self::IS,
			],
			'IS_NOT'       => [
				'description' => __( 'Evaluates values that do NOT match the comparison value.', 'wp-graphql-gravity-forms' ),
				'value'       => self::IS_NOT,
			],
			'CONTAINS'     => [
				'description' => __( 'Evaluates values that CONTAIN the comparison value.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CONTAINS,
			],
			'GREATER_THAN' => [
				'description' => __( 'Evaluates values that are GREATER than the comparison value.', 'wp-graphql-gravity-forms' ),
				'value'       => self::GREATER_THAN,
			],
			'LESS_THAN'    => [
				'description' => __( 'Evaluates values that are LESS than the comparison value.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LESS_THAN,
			],
			'STARTS_WITH'  => [
				'description' => __( 'Evaluates values that START with the comparison value.', 'wp-graphql-gravity-forms' ),
				'value'       => self::STARTS_WITH,
			],
			'ENDS_WITH'    => [
				'description' => __( 'Evaluates values that END with the comparison value.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ENDS_WITH,
			],
		];
	}
}
