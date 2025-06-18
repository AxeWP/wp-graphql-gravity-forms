<?php
/**
 * GraphQL Input Type - CheckboxFieldInput
 * Input fields for a single checkbox.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - CheckboxFieldInput
 */
class CheckboxFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'CheckboxFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for a single checkbox.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'inputId' => [
				'type'        => 'Float',
				'description' => static fn () => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'   => [
				'type'        => 'String',
				'description' => static fn () => __( 'Input value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
