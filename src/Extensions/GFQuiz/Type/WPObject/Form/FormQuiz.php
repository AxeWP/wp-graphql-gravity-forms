<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Settings
 *
 * @see https://docs.gravityforms.com/configure-quiz-settings/
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\Form
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\Form;

use WPGraphQL\AppContext;
use WPGraphQL\GF\Extensions\GFQuiz\Type\Enum\QuizFieldGradingTypeEnum;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\Form\Form;

/**
 * Class - FormConfirmation
 */
class FormQuiz extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormQuiz';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'quiz';

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		parent::register();

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Quiz-specific settings that will affect ALL Quiz fields in the form.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'failConfirmation'               => [
				'type'        => FormQuizConfirmation::$type,
				'description' => __( 'The message to display if the quiz grade is below the Pass Percentage. Only used if grading is set to `PASSFAIL`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?array {
					// Return null if the grading type is not PASSFAIL.
					if ( isset( $source['grading'] ) && 'passfail' !== $source['grading'] ) {
						return null;
					}
					return [
						'isAutoformatted' => empty( $source['failConfirmationDisableAutoformat'] ),
						'message'         => empty( $source['failConfirmationMessage'] ) ? null : $source['failConfirmationMessage'],
					];
				},
			],
			'grades'                         => [
				'type'        => [ 'list_of' => FormQuizGrades::$type ],
				'description' => __( 'The letter grades to be assigned based on the percentage score achieved. Only used if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					// Return null if the grading type is not LETTER.
					if ( isset( $source['grading'] ) && 'letter' !== $source['grading'] ) {
						return null;
					}
					return empty( $source['grades'] ) ? null : $source['grades'];
				},
			],
			'gradingType'                    => [
				'type'        => QuizFieldGradingTypeEnum::$type,
				'description' => __( 'The quiz grading type. Defaults to `NONE`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => empty( $source['grading'] ) ? null : $source['grading'],
			],
			'hasInstantFeedback'             => [
				'type'        => 'Boolean',
				'description' => __( 'Display correct or incorrect indicator and explanation (if any) immediately after answer selection. This setting only applies to radio button quiz fields and it is intended for training applications and trivial quizzes. It should not be considered a secure option for critical testing requirements.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source['instantFeedback'] ),
			],
			'hasLetterConfirmationMessage'   => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to display a confirmation message upon submission of the quiz form. Only used if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?bool {
					// Return null if the grading type is not LETTER.
					if ( isset( $source['grading'] ) && 'letter' !== $source['grading'] ) {
						return null;
					}

					return ! empty( $source['letterConfirmationMessage'] );
				},
			],
			'hasPassFailConfirmationMessage' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to display a confirmation message upon submission of the quiz form. Only used if grading is set to `PASSFAIL`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?bool {
					// Return null if the grading type is not PASSFAIL.
					if ( isset( $source['grading'] ) && 'passfail' !== $source['grading'] ) {
						return null;
					}

					return ! empty( $source['passfailDisplayConfirmation'] );
				},
			],
			'isShuffleFieldsEnabled'         => [
				'type'        => 'Boolean',
				'description' => __( 'Randomize the order of the quiz fields on this form each time the form is loaded.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source['shuffleFields'] ),
			],
			'letterConfirmation'             => [
				'type'        => FormQuizConfirmation::$type,
				'description' => __( 'The confirmation message to display if `grading` is set to `LETTER`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?array {
					// Return null if the grading type is not LETTER.
					if ( isset( $source['grading'] ) && 'letter' !== $source['grading'] ) {
						return null;
					}

					return empty( $source['letterDisplayConfirmation'] ) ? null : [
						'isAutoformatted' => empty( $source['letterConfirmationDisableAutoformat'] ),
						'message'         => empty( $source['letterConfirmationMessage'] ) ? null : $source['letterConfirmationMessage'],
					];
				},
			],
			'maxScore'                       => [
				'type'        => 'Float',
				'description' => __( 'The maximum score for this form.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ): ?float {
					return ( gf_quiz() )->get_max_score( $context->gfForm->form ) ?: null;
				},
			],
			'passPercent'                    => [
				'type'        => 'Int',
				'description' => __( "The percentage score the user must equal or exceed to be considered to have 'passed.' Only used if `grading` is set to `PASSFAIL`.", 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?int {
					// Return null if the grading type is not PASSFAIL.
					if ( isset( $source['grading'] ) && 'passfail' !== $source['grading'] ) {
						return null;
					}
					return empty( $source['passPercent'] ) ? null : (int) $source['passPercent'];
				},
			],
			'passConfirmation'               => [
				'type'        => FormQuizConfirmation::$type,
				'description' => __( 'The message to display if the quiz grade is above or equal to the Pass Percentage. Only used if grading is set to `PASSFAIL`.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ): ?array {
					// Return null if the grading type is not PASSFAIL.
					if ( isset( $source['grading'] ) && 'passfail' !== $source['grading'] ) {
						return null;
					}
					return [
						'isAutoformatted' => empty( $source['passConfirmationDisableAutoformat'] ),
						'message'         => empty( $source['passConfirmationMessage'] ) ? null : $source['passConfirmationMessage'],
					];
				},
			],
		];
	}

	/**
	 * Register quiz.
	 */
	public static function register_field(): void {
		register_graphql_field(
			Form::$type,
			self::$field_name,
			[
				'type'        => static::$type,
				'description' => __( 'Quiz-specific settings that will affect ALL Quiz fields in the form. Requires Gravity Forms Quiz addon.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ): ?array {
					// FYI: If a user doesn't explicitly save the quiz settings on the backend, this will be null.
					$context->gfForm = $source;
					return empty( $source->quiz ) ? null : $source->quiz;
				},
			]
		);
	}
}
