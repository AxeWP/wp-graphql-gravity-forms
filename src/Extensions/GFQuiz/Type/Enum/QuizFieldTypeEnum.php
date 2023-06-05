<?php
/**
 * Enum Type - QuizFieldTypeEnum
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\Enum,
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\Enum;

use WPGraphQL\GF\Type\Enum\AbstractEnum;

/**
 * Class - QuizFieldTypeEnum
 */
class QuizFieldTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'QuizFieldTypeEnum';

	// Individual elements.
	public const CHECKBOX = 'checkbox';
	public const RADIO    = 'radio';
	public const SELECT   = 'select';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The Gravity Forms field type used to display the current Quiz Field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'CHECKBOX' => [
				'description' => __( 'Gravity Forms `CheckboxField`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CHECKBOX,
			],
			'RADIO'    => [
				'description' => __( 'Gravity Forms `RadioField`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RADIO,
			],
			'SELECT'   => [
				'description' => __( 'Gravity Forms `SelectField`.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SELECT,
			],
		];
	}
}
