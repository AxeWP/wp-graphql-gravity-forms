<?php
/**
 * GraphQL Input Type - CheckboxInput
 * Input fields for a single checkbox.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

/**
 * Class - CheckboxInput
 */
class CheckboxInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'CheckboxInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Input fields for a single checkbox.', 'wp-graphql-gravity-forms' );
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
