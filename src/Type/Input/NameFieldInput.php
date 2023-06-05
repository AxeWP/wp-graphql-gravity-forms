<?php
/**
 * GraphQL Input Type - NameFieldInput
 * Input fields for name field.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 */

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
				'description' => __( 'Prefix, such as Mr., Mrs. etc.', 'wp-graphql-gravity-forms' ),
			],
			'first'  => [
				'type'        => 'String',
				'description' => __( 'First name.', 'wp-graphql-gravity-forms' ),
			],
			'middle' => [
				'type'        => 'String',
				'description' => __( 'Middle name.', 'wp-graphql-gravity-forms' ),
			],
			'last'   => [
				'type'        => 'String',
				'description' => __( 'Last name.', 'wp-graphql-gravity-forms' ),
			],
			'suffix' => [
				'type'        => 'String',
				'description' => __( 'Suffix, such as Sr., Jr. etc.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
