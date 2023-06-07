<?php
/**
 * GraphQL Input Type - ListFieldInput
 * Input fields for a single List field item.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - ListFieldInput
 */
class ListFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ListFieldInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for a single List field item.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'rowValues' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Input values for the specific listField row.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
