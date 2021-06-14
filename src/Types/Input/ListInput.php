<?php
/**
 * GraphQL Input Type - ListInput
 * Input fields for a single List field item.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 * @since   0.3.0 Deprecate `values` in favor of `rowValues`.
 */

namespace WPGraphQLGravityForms\Types\Input;

/**
 * Class - ListInput
 */
class ListInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ListInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Input fields for a single List field item.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'values'    => [
				'type'              => [ 'list_of' => 'String' ],
				'description'       => __( 'Input value. Deprecated - please use `rowValues` instead.', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => __( 'Please use `rowValues` instead.', 'wp-graphql-gravity-forms' ),
			],
			'rowValues' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Input values for the specific listField row.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
