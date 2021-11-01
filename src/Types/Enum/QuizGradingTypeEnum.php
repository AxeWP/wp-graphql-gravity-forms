<?php
/**
 * Enum Type - QuizGradingTypeEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.9.1
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - QuizGradingTypeEnum
 */
class QuizGradingTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'QuizGradingTypeEnum';

	// Individual elements.
	const NONE     = 'none';
	const PASSFAIL = 'passfail';
	const LETTER   = 'letter';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Type of grading system used by Gravity Forms Quiz. Default is `NONE`.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function get_values() : array {
		return [
			'NONE'     => [
				'description' => __( 'No grading.', 'wp-graphql-gravity-forms' ),
				'value'       => self::NONE,
			],
			'PASSFAIL' => [
				'description' => __( 'Pass-fail grading system.', 'wp-graphql-gravity-forms' ),
				'value'       => self::PASSFAIL,
			],
			'LETTER'   => [
				'description' => __( 'Letter grading system.', 'wp-graphql-gravity-forms' ),
				'value'       => self::LETTER,
			],
		];
	}
}
