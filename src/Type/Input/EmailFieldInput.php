<?php
/**
 * GraphQL Input Type - EmailFieldInput
 * Input fields for a single checkbox.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - EmailFieldInput
 */
class EmailFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EmailFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for email field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'value'             => [
				'type'        => 'String',
				'description' => __( 'Email input value.', 'wp-graphql-gravity-forms' ),
			],
			'confirmationValue' => [
				'type'        => 'String',
				'description' => __( 'Email confirmation input value. Only used when email confirmation is enabled.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
