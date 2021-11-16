<?php
/**
 * GraphQL Object Type - EntryQuizResults
 *
 * @package WPGraphQL\GF\Types\EntryQuizResults
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Types\Entry;

use WPGraphQL\GF\Types\AbstractObject;

/**
 * Class - EntryQuizResults
 */
class EntryQuizResults extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EntryQuizResults';

	/**
	 * Gets the GraphQL type description.
	 */
	public function get_type_description() : string {
		return __( 'The quiz results for the entry. Requires Gravity Forms Quiz to be enabled.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return [
			'score'   => [
				'type'        => 'Int',
				'description' => __( 'The raw quiz score.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?int {
					return $root['gquiz_score'] ?? null;
				},
			],
			'percent' => [
				'type'        => 'Int',
				'description' => __( 'The quiz score as a percent.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?int {
					return $root['gquiz_percent'] ?? null;
				},
			],
			'grade'   => [
				'type'        => 'String',
				'description' => __( 'The quiz score as a letter grade.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?string {
					return $root['gquiz_grade'] ?? null;
				},
			],
			'isPass'  => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the quiz score meets the assigned passing threshold.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function( $root ) : ?bool {
					return $root['gquiz_is_pass'] ?? null;
				},
			],
		];
	}
}
