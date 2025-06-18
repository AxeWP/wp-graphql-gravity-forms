<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Results Grade Count
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults
 * @since   0.10.4
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - QuizResultsGradeCount
 */
class QuizResultsGradeCount extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfQuizResultsGradeCount';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'A quiz Grade and its count.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'count' => [
				'type'        => 'Int',
				'description' => static fn () => __( 'The number of entries that received this grade across all entries received.', 'wp-graphql-gravity-forms' ),
			],
			'grade' => [
				'type'        => 'String',
				'description' => static fn () => __( 'The quiz grade.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
