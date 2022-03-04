<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Results Field Count
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults
 * @since   0.10.4
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\QuizResults;

use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - QuizResultsFieldCount
 */
class QuizResultsFieldCount extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfQuizResultsFieldCount';

	/**
	 * Register Object type to GraphQL schema.
	 *
	 * @param  TypeRegistry $type_registry .
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			[
				'connections'     => [
					'formField' => [
						'toType'   => 'QuizField',
						'oneToOne' => true,
						'resolve'  => static function( $source ) {
							return [ 'node' => $source['field'] ];
						},
					],
				],
				'description'     => static::get_description(),
				'fields'          => static::get_fields(),
				'eagerlyLoadType' => static::$should_load_eagerly,
			]
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The quiz results summary for an individual quiz field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
