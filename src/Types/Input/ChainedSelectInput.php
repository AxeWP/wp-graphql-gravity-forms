<?php
/**
 * GraphQL Input Type - ChainedSelectInput
 * Input fields for a single ChainedSelect.
 *
 * @package WPGraphQL\GF\Types\Input
 * @since   0.3.0
 */

namespace WPGraphQL\GF\Types\Input;

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
	 */
	public function get_type_fields() : array {
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
