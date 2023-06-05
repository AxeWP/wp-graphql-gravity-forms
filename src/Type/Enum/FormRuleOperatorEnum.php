<?php
/**
 * Enum Type - FormRuleOperatorEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormRuleOperatorEnum
 */
class FormRuleOperatorEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormRuleOperatorEnum';

	// Individual elements.
	public const IS           = 'is';
	public const IS_NOT       = 'isnot';
	public const CONTAINS     = 'contains';
	public const GREATER_THAN = '>';
	public const LESS_THAN    = '<';
	public const STARTS_WITH  = 'starts_with';
	public const ENDS_WITH    = 'ends_with';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Operator to be used when evaluating logic rules.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
