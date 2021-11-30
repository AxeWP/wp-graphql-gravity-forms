<?php
/**
 * GraphQL Object Type - Quiz Field
 *
 * @see https://docs.gravityforms.com/quiz-field/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\Enum\QuizFieldTypeEnum;
use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;

/**
 * Class - QuizField
 */
class QuizField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'QuizField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'quiz';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Quiz field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\AllowsPrepopulateProperty::get(),
			FieldProperty\AutocompleteAttributeProperty::get(),
			FieldProperty\ConditionalLogicProperty::get(),
			FieldProperty\CssClassProperty::get(),
			FieldProperty\DefaultValueProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\EnableAutocompleteProperty::get(),
			FieldProperty\EnableChoiceValueProperty::get(),
			FieldProperty\EnableEnhancedUiProperty::get(),
			FieldProperty\EnableSelectAllProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\InputNameProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\LabelPlacementProperty::get(),
			FieldProperty\PlaceholderProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'answerExplanation'          => [
					'type'        => 'String',
					'description' => __( 'The explanation for the correct answer and/or incorrect answers.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) : ?string {
						return $root['gquizAnswerExplanation'] ?? null;
					},
				],
				'choices'                    => [
					'type'        => [ 'list_of' => FieldProperty\QuizChoiceProperty::$type ],
					'description' => __( 'Choices used to populate the dropdown field. These can be nested multiple levels deep.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) {
						if ( ! $root['gquizWeightedScoreEnabled'] ) {
							return array_map(
								function( array $choice ) : array {
									$choice['gquizWeight'] = (float) $choice['gquizIsCorrect'];
									return $choice;
								},
								$root['choices']
							);
						}
						return $root['choices'];
					},
				],
				'enableRandomizeQuizChoices' => [
					'type'        => 'Boolean',
					'description' => __( 'Whether to randomize the order in which the answers are displayed to the user.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) : bool {
						return (bool) $root['gquizEnableRandomizeQuizChoices'];
					},
				],
				'enableWeightedScore'        => [
					'type'        => 'Boolean',
					'description' => __( 'If this setting is disabled then the response will be awarded a score of 1 if correct and 0 if incorrect.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) : bool {
						return (bool) $root['gquizWeightedScoreEnabled'];
					},
				],
				'inputs'                     => [
					'type'        => [ 'list_of' => FieldProperty\CheckboxInputProperty::$type ],
					'description' => __( 'List of inputs. Checkboxes are treated as multi-input fields, since each checkbox item is stored separately.', 'wp-graphql-gravity-forms' ),
				],
				'quizFieldType'              => [
					'type'        => QuizFieldTypeEnum::$type,
					'description' => __( 'The Gravity Forms field type used by the Quiz Field.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) : string {
						return $root['gquizFieldType'];
					},
				],
				'showAnswerExplanation'      => [
					'type'        => 'Boolean',
					'description' => __( 'Whether to show an answer explanation.', 'wp-graphql-gravity-forms' ),
					'resolve'     => static function( $root ) : bool {
						return (bool) $root['gquizShowAnswerExplanation'];
					},
				],
			],
		);
	}
}
