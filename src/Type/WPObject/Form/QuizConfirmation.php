<?php
/**
 * GraphQL Object Type - Gravity Forms Quiz Settings
 *
 * @see https://docs.gravityforms.com/configure-quiz-settings/
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.9.1
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - FormConfirmation
 */
class QuizConfirmation extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'QuizConfirmation';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The Quiz Confirmation message data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'isAutoformatted' => [
				'type'        => 'String',
				'description' => __( 'Whether autoformatting is enabled for the confirmation message.', 'wp-graphql-gravity-forms' ),
			],
			'message'         => [
				'type'        => 'Int',
				'description' => __( 'The message to display.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
