<?php
/**
 * GraphQL Input Type - ConsentFieldInput
 * Input field for Consent.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - ConsentFieldInput
 */
class ConsentFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ConsentFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Input field for Consent.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'value'   => [
				'type'        => 'Boolean',
				'description' => __( 'Input value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
