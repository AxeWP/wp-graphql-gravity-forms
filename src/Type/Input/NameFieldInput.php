<?php
/**
 * GraphQL Input Type - NameFieldInput
 * Input fields for name field.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - NameFieldInput
 */
class NameFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'NameFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for name field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'prefix' => [
				'type'        => 'String',
				'description' => static fn () => __( 'Prefix, such as Mr., Mrs. etc.', 'wp-graphql-gravity-forms' ),
			],
			'first'  => [
				'type'        => 'String',
				'description' => static fn () => __( 'First name.', 'wp-graphql-gravity-forms' ),
			],
			'middle' => [
				'type'        => 'String',
				'description' => static fn () => __( 'Middle name.', 'wp-graphql-gravity-forms' ),
			],
			'last'   => [
				'type'        => 'String',
				'description' => static fn () => __( 'Last name.', 'wp-graphql-gravity-forms' ),
			],
			'suffix' => [
				'type'        => 'String',
				'description' => static fn () => __( 'Suffix, such as Sr., Jr. etc.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
