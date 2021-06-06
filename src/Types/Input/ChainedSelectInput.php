<?php
/**
 * GraphQL Input Type - ChainedSelectInput
 * Input fields for a single ChainedSelect.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.3.0
 */

namespace WPGraphQLGravityForms\Types\Input;

/**
 * Class - ChainedSelectInput
 */
class ChainedSelectInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ChainedSelectInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Input fields for a single ChainedSelect.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_properties() : array {
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
