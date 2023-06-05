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
 * Class - QuizResultsChoiceCount
 */
class QuizResultsChoiceCount extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfQuizResultsChoiceCount';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The response counts for individual quiz fields.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'count' => [
				'type'        => 'Int',
				'description' => __( 'The number of entries with this choice provided.', 'wp-graphql-gravity-forms' ),
			],
			'text'  => [
				'type'        => 'String',
				'description' => __( 'The choice text.', 'wp-graphql-gravity-forms' ),
			],
			'value' => [
				'type'        => 'String',
				'description' => __( 'The internal value used to represent the quiz choice.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
