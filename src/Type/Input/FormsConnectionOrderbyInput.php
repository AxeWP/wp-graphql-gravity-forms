<?php
/**
 * GraphQL Input Type - FormsConnectionOrderbyInput
 * Sorting input type for Forms queries.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - FormsConnectionOrderbyInput
 */
class FormsConnectionOrderbyInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormsConnectionOrderbyInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Options for ordering the connection.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			// @todo switch to enum
			'field' => [
				'type'        => 'String',
				'description' => __( 'The field name used to sort the results.', 'wp-graphql-gravity-forms' ),
			],
			'order' => [
				'type'        => 'OrderEnum',
				'description' => __( 'The cardinality of the order of the connection.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
