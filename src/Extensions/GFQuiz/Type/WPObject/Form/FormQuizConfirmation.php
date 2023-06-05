<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Settings
 *
 * @see https://docs.gravityforms.com/configure-quiz-settings/
 *
 * @package WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\Form
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFQuiz\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormConfirmation
 */
class FormQuizConfirmation extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormQuizConfirmation';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The Quiz Confirmation message data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isAutoformatted' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether autoformatting is enabled for the confirmation message.', 'wp-graphql-gravity-forms' ),
			],
			'message'         => [
				'type'        => 'String',
				'description' => __( 'The message to display.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
