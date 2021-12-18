<?php
/**
 * GraphQL Object Type - EntryQuizResults
 *
 * @package WPGraphQL\GF\Type\WPObject\EntryQuizResults
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Type\WPObject\Entry;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - EntryQuizResults
 */
class EntryQuizResults extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryQuizResults';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The quiz results for the entry. Requires Gravity Forms Quiz to be enabled.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'score'          => [
				'type'        => 'Int',
				'description' => __( 'The raw quiz score.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?int {
					return $root['gquiz_score'] ?? null;
				},
			],
			'percent'        => [
				'type'        => 'Int',
				'description' => __( 'The quiz score as a percent.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?int {
					return $root['gquiz_percent'] ?? null;
				},
			],
			'grade'          => [
				'type'        => 'String',
				'description' => __( 'The quiz score as a letter grade.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?string {
					return $root['gquiz_grade'] ?? null;
				},
			],
			'isPassingScore' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the quiz score meets the assigned passing threshold.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?bool {
					return $root['gquiz_is_pass'] ?? null;
				},
			],
		];
	}
}
