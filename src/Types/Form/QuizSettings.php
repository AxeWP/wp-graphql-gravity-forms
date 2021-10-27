<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Settings
 *
 * @see https://docs.gravityforms.com/configure-quiz-settings/
 *
 * @package WPGraphQLGravityForms\Types\Form
 * @since   0.9.0
 */

namespace WPGraphQLGravityForms\Types\Form;

use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Enum\QuizGradingTypeEnum;

/**
 * Class - FormConfirmation
 */
class QuizSettings extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'QuizSettings';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'Quiz-specific settings that will affect ALL Quiz fields in the form.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL fields for the type.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'shuffleFields'                       => [
				'type'        => 'Boolean',
				'description' => __( 'Randomize the order of the quiz fields on this form each time the form is loaded.', 'wp-graphql-gravity-forms' ),
			],
			'instantFeedback'                     => [
				'type'        => 'Boolean',
				'description' => __( 'Display correct or incorrect indicator and explanation (if any) immediately after answer selection. This setting only applies to radio button quiz fields and it is intended for training applications and trivial quizzes. It should not be considered a secure option for critical testing requirements.', 'wp-graphql-gravity-forms' ),
			],
			'grading'                             => [
				'type'        => QuizGradingTypeEnum::$type,
				'description' => __( 'The quiz grading type. Defaults to `NONE`.', 'wp-graphql-gravity-forms' ),
			],
			'grades'                              => [
				'type'        => [ 'list_of' => QuizGrades::$type ],
				'description' => __( 'The letter grades to be assigned based on the percentage score achieved. Only used if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' ),
			],
			'passPercent'                         => [
				'type'        => 'Integer',
				'description' => __( "The percentage score the user must equal or exceed to be considered to have 'passed.' Only used if `grading` is set to `PASSFAIL`.", 'wp-graphql-gravity-forms' ),
			],
			'passfailDisplayConfirmation'         => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to display a confirmation message upon submission of the quiz form. Only used if grading is set to `PASSFAIL`.', 'wp-graphql-gravity-forms' ),
			],
			'passConfirmationMessage'             => [
				'type'        => 'String',
				'description' => __( 'The message to display if the quiz grade is above or equal to the Pass Percentage. Only used if grading is set to `PASSFAIL`.', 'wp-graphql-gravity-forms' ),
			],
			'passConfirmationDisableAutoformat'   => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to disable autoformatting for the Passing confirmation message.', 'wp-graphql-gravity-forms' ),
			],
			'failConfirmationMessage'             => [
				'type'        => 'String',
				'description' => __( 'The message to display if the quiz grade is below the Pass Percentage. Only used if grading is set to `PASSFAIL`.', 'wp-graphql-gravity-forms' ),
			],
			'failConfirmationDisableAutoformat'   => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to disable autoformatting for the Failing confirmation message.', 'wp-graphql-gravity-forms' ),
			],
			'letterDisplayConfirmation'           => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to display a confirmation message upon submission of the quiz form. Only used if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' ),
			],
			'letterConfirmationMessage'           => [
				'type'        => 'String',
				'description' => __( 'The confirmation message to display. Only used if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' ),
			],
			'letterConfirmationDisableAutoformat' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to disable autoformatting for the Letter confirmation message', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
