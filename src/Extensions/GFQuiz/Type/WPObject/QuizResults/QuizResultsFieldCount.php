<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Results Field Count
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults
 * @since   0.10.4
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults;

use WPGraphQL\GF\Interfaces\TypeWithConnections;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - QuizResultsFieldCount
 */
class QuizResultsFieldCount extends AbstractObject implements TypeWithConnections {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfQuizResultsFieldCount';

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		$config = parent::get_type_config();

		$config['connections'] = self::get_connections();

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connections(): array {
		return [
			'formField' => [
				'toType'   => 'QuizField',
				'oneToOne' => true,
				'resolve'  => static function ( $source ): array {
					return [ 'node' => $source['field'] ];
				},
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The quiz results summary for an individual quiz field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'choiceCounts'   => [
				'type'        => [ 'list_of' => QuizResultsChoiceCount::$type ],
				'description' => __( 'A list of the individual responses and their counts.', 'wp-graphql-gravity-forms' ),
			],
			'correctCount'   => [
				'type'        => 'Int',
				'description' => __( 'The number of correct responses across all entries received.', 'wp-graphql-gravity-forms' ),
			],
			'fieldId'        => [
				'type'        => 'Int',
				'description' => __( 'The quiz field ID.', 'wp-graphql-gravity-forms' ),
			],
			'incorrectCount' => [
				'type'        => 'Int',
				'description' => __( 'The number of incorrect responses across all entries received.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
