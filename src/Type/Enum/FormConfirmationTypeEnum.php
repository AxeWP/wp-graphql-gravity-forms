<?php
/**
 * Enum Type - FormConfirmationTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormConfirmationTypeEnum
 */
class FormConfirmationTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormConfirmationTypeEnum';

	// Individual elements.
	const MESSAGE  = 'message';
	const PAGE     = 'page';
	const REDIRECT = 'redirect';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Type of form confirmation to be used.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values() : array {
		return [
			'MESSAGE'  => [
				'description' => __( 'Use a confirmation "message".', 'wp-graphql-gravity-forms' ),
				'value'       => self::MESSAGE,
			],
			'PAGE'     => [
				'description' => __( 'Use a redirect to a different WordPress "page".', 'wp-graphql-gravity-forms' ),
				'value'       => self::PAGE,
			],
			'REDIRECT' => [
				'description' => __( 'Use a "redirect" to a given URL.', 'wp-graphql-gravity-forms' ),
				'value'       => self::REDIRECT,
			],
		];
	}
}
