<?php
/**
 * GraphQL Object Type - Gravity Forms Login data.
 *
 * @package WPGraphQL\GF\Type\WPObject\Form
 * @since   0.10.0
 */

namespace WPGraphQL\GF\Type\WPObject\Form;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FormLogin
 */
class FormLogin extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormLogin';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Gravity Forms form login requirements data.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isLoginRequired'      => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the form is configured to be displayed only to logged in users.', 'wp-graphql-gravity-forms' ),
			],
			'loginRequiredMessage' => [
				'type'        => 'String',
				'description' => __( 'When `isLoginRequired` is set to true, this controls the message displayed when non-logged in user tries to access the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
