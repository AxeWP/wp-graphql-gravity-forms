<?php
/**
 * Maps the Gravity Forms Field setting to the appropriate field settings.
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\FormField\FieldProperty
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\FormField\FieldProperty;

use GF_Field;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ChoiceMapper;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\FieldProperties;

/**
 * Class - PropertyMapper
 */
class PropertyMapper {
	/**
	 * Maps the `gquiz_setting_choices` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function gquiz_setting_choices( GF_Field $field, array &$properties ) : void {
		$properties += [
			'hasWeightedScore' => [
				'type'        => 'Boolean',
				'description' => __( 'If this setting is disabled then the response will be awarded a score of 1 if correct and 0 if incorrect.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->gquizWeightedScoreEnabled ),
			],
		];

		$choice_fields  = [
			'isCorrect' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates the choice item is the correct answer.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->gquizIsCorrect ),
			],
		];
		$choice_fields += [
			'weight' => [
				'type'        => 'Float',
				'description' => __( 'The weighted score awarded for the choice.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( $source, array $args, AppContext $context ) {
					if ( isset( $context->gfField->gquizWeightedScoreEnabled ) && false === $context->gfField->gquizWeightedScoreEnabled ) {
						return (float) $source['gquizIsCorrect'];
					}

					return is_numeric( $source['gquizWeight'] ) ? (float) $source['gquizWeight'] : null;
				},
			],
		];
		$choice_fields += FieldProperties::choice_is_other();

		ChoiceMapper::add_fields_to_choice( $field, $choice_fields );
	}

	/**
	 * Maps the `gquiz_setting_show_answer_explanation` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function gquiz_setting_show_answer_explanation( GF_Field $field, array &$properties ) : void {
		$properties += [
			'shouldShowAnswerExplanation' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to show an answer explanation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->gquizShowAnswerExplanation ),
			],
		];

		$properties += [
			'answerExplanation' => [
				'type'        => 'String',
				'description' => __( 'The explanation for the correct answer and/or incorrect answers.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn ( $source ) : ?string => ! empty( $source->gquizAnswerExplanation ) ? $source->gquizAnswerExplanation : null,
			],
		];
	}

	/**
	 * Maps the `gquiz_setting_randomize_quiz_choices` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function gquiz_setting_randomize_quiz_choices( GF_Field $field, array &$properties ) : void {
		$properties += [
			'shouldRandomizeQuizChoices' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether to randomize the order in which the answers are displayed to the user.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) : bool => ! empty( $source->gquizEnableRandomizeQuizChoices ),
			],
		];
	}

	/**
	 * Maps the `gquiz_setting_question` to its field properties.
	 *
	 * @param GF_Field $field .
	 * @param array    $properties the existing properties array.
	 */
	public static function gquiz_setting_question( GF_Field $field, array &$properties ) : void {
		$properties += FieldProperties::label();
	}
}
