<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Settings
 *
 * @see https://docs.gravityforms.com/configure-quiz-settings/
 *
 * @package WPGraphQLGravityForms\Types\Form
 * @since   0.9.1
 */

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Types\AbstractObject;

/**
 * Class - FormConfirmation
 */
class QuizGrades extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'QuizGrades';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'The letter grades to be assigned based on the percentage score achieved. Only used if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
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
