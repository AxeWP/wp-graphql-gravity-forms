<?php
/**
 * GraphQL Input Type - ChainedSelectFieldInput
 * Input fields for a single ChainedSelect.
 *
 * @package WPGraphQL\GF\Extensions\GFChainedSelects\Type\Input
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFChainedSelects\Type\Input;

use WPGraphQL\GF\Type\Input\AbstractInput;

/**
 * Class - ChainedSelectFieldInput
 */
class ChainedSelectFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ChainedSelectFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for a single ChainedSelect.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'inputId' => [
				'type'        => 'Float',
				'description' => __( 'Input ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'   => [
				'type'        => 'String',
				'description' => __( 'Input value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
