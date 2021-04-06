<?php
/**
 * Enum Type - ConditionalLogicLogicTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - ConditionalLogicLogicTypeEnum
 */
class ConditionalLogicLogicTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ConditionalLogicLogicTypeEnum';

	// Individual elements.
	const ALL = 'all';
	const ANY = 'any';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Determines how to the rules should be evaluated.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
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
