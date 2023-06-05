<?php
/**
 * GraphQL Object Type - EntryQuizResults
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\EntryQuizResults
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\Entry;

use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\WPObject\AbstractObject;
use WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry;

/**
 * Class - EntryQuizResults
 */
class EntryQuizResults extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryQuizResults';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'quizResults';

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
		return __( 'The quiz results for the entry. Requires Gravity Forms Quiz to be enabled.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'score'          => [
				'type'        => 'Int',
				'description' => __( 'The raw quiz score.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $root ): ?int {
					return $root['gquiz_score'] ?? null;
				},
			],
			'percent'        => [
				'type'        => 'Int',
				'description' => __( 'The quiz score as a percent.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $root ): ?int {
					return $root['gquiz_percent'] ?? null;
				},
			],
			'grade'          => [
				'type'        => 'String',
				'description' => __( 'The quiz score as a letter grade.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $root ): ?string {
					return $root['gquiz_grade'] ?? null;
				},
			],
			'isPassingScore' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the quiz score meets the assigned passing threshold.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $root ): ?bool {
					return $root['gquiz_is_pass'] ?? null;
				},
			],
		];
	}

	/**
	 * Register quizResults.
	 */
	public static function register_field(): void {
		register_graphql_field(
			SubmittedEntry::$type,
			self::$field_name,
			[
				'type'        => static::$type,
				'description' => __( 'The quiz results for the entry. Requires Gravity Forms Quiz to be enabled.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					return $source;
				},
			]
		);
	}
}
