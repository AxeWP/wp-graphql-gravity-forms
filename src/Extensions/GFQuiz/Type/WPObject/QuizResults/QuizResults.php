<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Results Summary
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults
 * @since   0.10.4
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults;

use GFAPI;
use GFCommon;
use GFFormsModel;
use GFQuiz;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\Form\Form;

/**
 * Class - QuizResults
 */
class QuizResults extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfQuizResults';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'quizResults';

	// @todo grab search criteria from connection args.
	/**
	 * @var array<string, string>
	 */
	private const SEARCH_CRITERIA = [ 'status' => 'active' ];

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
		return __( 'The quiz results for all entries.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'averagePercentage' => [
				'type'        => 'Float',
				'description' => __( 'Average percentage score as calculated across all entries received.', 'wp-graphql-gravity-forms' ),
			],
			'averageScore'      => [
				'type'        => 'Float',
				'description' => __( 'Average score as calculated across all entries received.', 'wp-graphql-gravity-forms' ),
			],
			'entryCount'        => [
				'type'        => 'Int',
				'description' => __( 'Quantity of all the entries received for this quiz.', 'wp-graphql-gravity-forms' ),
			],
			'fieldCounts'       => [
				'type'        => [ 'list_of' => QuizResultsFieldCount::$type ],
				'description' => __( 'A list of fields and frequency of each answer provided.', 'wp-graphql-gravity-forms' ),
			],
			'gradeCounts'       => [
				'type'        => [ 'list_of' => QuizResultsGradeCount::$type ],
				'description' => __( 'If using letter grades, will show the frequency of each letter grade across all entries received.', 'wp-graphql-gravity-forms' ),
			],
			'passRate'          => [
				'type'        => 'Float',
				'description' => __( 'The pass-fail rate for all the entries received for this quiz.', 'wp-graphql-gravity-forms' ),
			],
			'scoreCounts'       => [
				'type'        => [ 'list_of' => QuizResultsScoreCount::$type ],
				'description' => __( 'Displays a frequency bar chart showing the spread of each quiz score.', 'wp-graphql-gravity-forms' ),
			],
			'sum'               => [
				'type'        => 'Float',
				'description' => __( 'The total sum of all entry scores. Useful for calculating custom result statistics.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Register quizResults.
	 */
	public static function register_field(): void {
		$from_type = sprintf( '%1$sTo%2$sConnection', Form::$type, Entry::$type );

		register_graphql_field(
			$from_type,
			self::$field_name,
			[
				'type'        => static::$type,
				'description' => __( 'The quiz results for the given form.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source, array $args, AppContext $context ) {
					$form           = $context->gfForm->form;
					$quiz           = GFQuiz::get_instance();
					$results_config = $quiz->get_results_page_config();

					$results = self::get_quiz_results_data( $form, $results_config );

					return self::prepare_results_data( $results, $form );
				},
			]
		);
	}

	/**
	 * Gets the Quiz Results data.
	 *
	 * @param array $form .
	 * @param array $results_config the GFQuiz config array.
	 */
	protected static function get_quiz_results_data( array $form, array $results_config ): array {
		if ( ! class_exists( 'GFResults' ) ) {
			require_once GFCommon::get_base_path() . '/includes/addon/class-gf-results.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
		}

		if ( isset( $results_config['callbacks']['filters'] ) ) {
			add_filter( 'gform_filters_pre_results', $results_config['callbacks']['filters'], 10, 2 );
		}

		$gf_results = new \GFResults( 'gravityformsquiz', $results_config );

		$fields = $results_config['callbacks']['fields']( $form );

		return $gf_results->get_results_data( $form, $fields, self::SEARCH_CRITERIA );
	}

	/**
	 * Transforms the results data for the GraphQL response.
	 *
	 * @param array $data The Quiz Results data.
	 * @param array $form .
	 */
	protected static function prepare_results_data( array $data, array $form ): array {
		if ( empty( $data['entry_count'] ) ) {
			return [];
		}

		$entry_count = (int) $data['entry_count'];
		$sum         = ! empty( $data['sum'] ) ? (int) $data['sum'] : 0;
		$max_score   = isset( $data['score_frequencies'] ) && is_array( $data['score_frequencies'] ) ? max( array_keys( $data['score_frequencies'] ) ) : null;
		$pass_rate   = $data['pass_rate'] ?? null;

		$average_score = round( $sum / $entry_count, 2 );

		$average_percent = ! empty( $max_score ) && is_int( $max_score ) ? round( ( $sum / ( $max_score * $entry_count ) ) * 100 ) : null;

		$score_frequencies = empty( $data['score_frequencies'] ) ? null : self::map_score_frequencies( $data['score_frequencies'] );

		$grade_frequencies = empty( $data['grade_frequencies'] ) ? null : self::map_grade_frequencies( $data['grade_frequencies'] );

		$field_counts = empty( $data['field_data'] ) ? null : self::map_field_data( $data['field_data'], $form, $entry_count );

		return [
			'averagePercentage' => $average_percent,
			'averageScore'      => $average_score,
			'entryCount'        => $entry_count,
			'fieldCounts'       => $field_counts,
			'gradeCounts'       => $grade_frequencies,
			'passRate'          => $pass_rate,
			'scoreCounts'       => $score_frequencies,
			'sum'               => $sum,
		];
	}

	/**
	 * Maps the score frequencies array into a format WPGraphQL can understand.
	 *
	 * @param array $score_frequencies the score frequences array. E.g. `[ $score => $count ]`.
	 */
	private static function map_score_frequencies( array $score_frequencies ): array {
		return array_map(
			static fn ( $score, $count) => [
				'score' => $score,
				'count' => $count,
			],
			array_keys( $score_frequencies ),
			$score_frequencies
		);
	}

	/**
	 * Maps the grade frequencies array into a format WPGraphQL can understand.
	 *
	 * @param array $grade_frequencies the score frequences array. E.g. `[ $grade => $count ]`.
	 */
	private static function map_grade_frequencies( array $grade_frequencies ): array {
		return array_map(
			static fn ( $grade, $count) => [
				'grade' => $grade,
				'count' => $count,
			],
			array_keys( $grade_frequencies ),
			$grade_frequencies
		);
	}

	/**
	 * Maps the field data array to a format WPGraphQL can understand.
	 *
	 * @param array   $field_data . The field data array.
	 * @param array   $form .
	 * @param integer $entry_count . The number of submitted entries.
	 */
	private static function map_field_data( array $field_data, array $form, int $entry_count ): array {
		return array_map(
			static function ( $id, $data ) use ( $entry_count, $form ): array {
				$field = GFAPI::get_field( $form, $id );
				// Move the totals out of $data, since we  dont need them in the choice array.
				$totals = $data['totals'];
				unset( $data['totals'] );
				$choice_counts = array_map(
					static function ( $count, $value ) use ( $field ): array {
						return [
							'count' => $count,
							'value' => $value,
							'text'  => GFFormsModel::get_choice_text( $field, $value ),
						];
					},
					$data,
					array_keys( $data )
				);

				return [
					'choiceCounts'   => $choice_counts,
					'correctCount'   => $totals['correct'],
					'field'          => $field,
					'fieldId'        => $id,
					'incorrectCount' => $entry_count - $totals['correct'],
				];
			},
			array_keys( $field_data ),
			$field_data,
		);
	}
}
