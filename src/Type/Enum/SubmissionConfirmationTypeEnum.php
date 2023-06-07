<?php
/**
 * Enum Type - SubmissionConfirmationTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum
 * @since   0.11.1
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - SubmissionConfirmationTypeEnum
 */
class SubmissionConfirmationTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'SubmissionConfirmationTypeEnum';

	// Individual elements.
	public const MESSAGE  = 'message';
	public const REDIRECT = 'redirect';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of confirmation returned by the submission.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'MESSAGE'  => [
				'description' => __( 'A confirmation "message".', 'wp-graphql-gravity-forms' ),
				'value'       => self::MESSAGE,
			],
			'REDIRECT' => [
				'description' => __( 'A "redirect" to a given URL.', 'wp-graphql-gravity-forms' ),
				'value'       => self::REDIRECT,
			],
		];
	}
}
