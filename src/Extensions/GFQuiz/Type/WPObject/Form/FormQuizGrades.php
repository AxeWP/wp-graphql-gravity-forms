<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Settings
 *
 * @see https://docs.gravityforms.com/configure-quiz-settings/
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\Form
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormQuizGrades
 */
class FormQuizGrades extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormQuizGrades';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The letter grades to be assigned based on the percentage score achieved. Only used if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'text'  => [
				'type'        => 'String',
				'description' => __( 'The grade label.', 'wp-graphql-gravity-forms' ),
			],
			'value' => [
				'type'        => 'Int',
				'description' => __( 'The minimum percentage score required to achieve this grade.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
