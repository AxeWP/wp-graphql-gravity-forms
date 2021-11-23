<?php
/**
 * GraphQL Input Type - ChainedSelectInput
 * Input fields for a single ChainedSelect.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.3.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - ChainedSelectInput
 */
class ChainedSelectInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Input fields for a single ChainedSelect.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'inputId' => [
				'type'        => 'Float',
				'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'   => [
				'type'        => 'String',
				'description' => __( 'Input value', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
