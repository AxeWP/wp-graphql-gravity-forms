<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Results Score Count
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults
 * @since   0.10.4
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - QuizResultsScoreCount
 */
class QuizResultsScoreCount extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfQuizResultsScoreCount';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'A quiz score and its count.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'count' => [
				'type'        => 'Int',
				'description' => __( 'The number of entries that received this score across all entries received.', 'wp-graphql-gravity-forms' ),
			],
			'score' => [
				'type'        => 'Float',
				'description' => __( 'The quiz score.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
