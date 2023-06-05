<?php
/**
 * Enum Type - ConditionalLogicLogicTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - ConditionalLogicLogicTypeEnum
 */
class ConditionalLogicLogicTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ConditionalLogicLogicTypeEnum';

	// Individual elements.
	public const ALL = 'all';
	public const ANY = 'any';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Determines how to the rules should be evaluated.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'ALL' => [
				'description' => __( 'Evaulate all logic rules.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ALL,
			],
			'ANY' => [
				'description' => __( 'Evaluate any logic rule.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ANY,
			],
		];
	}
}
