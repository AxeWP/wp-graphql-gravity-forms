<?php
/**
 * GraphQL Input Type - ListInput
 * Input fields for a single List field item.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.0.1
 * @since   0.3.0 Deprecate `values` in favor of `rowValues`.
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - ListInput
 */
class ListInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ListInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Input fields for a single List field item.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'rowValues' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Input values for the specific listField row.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
