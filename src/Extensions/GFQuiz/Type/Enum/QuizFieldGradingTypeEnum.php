<?php
/**
 * Enum Type - QuizFieldGradingTypeEnum
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\Enum,
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\Enum;

use WPGraphQL\GF\Type\Enum\AbstractEnum;

/**
 * Class - QuizFieldGradingTypeEnum
 */
class QuizFieldGradingTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'QuizFieldGradingTypeEnum';

	// Individual elements.
	public const NONE     = 'none';
	public const PASSFAIL = 'passfail';
	public const LETTER   = 'letter';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of grading system used by Gravity Forms Quiz. Default is `NONE`.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
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
