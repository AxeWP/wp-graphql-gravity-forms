<?php
/**
 * GraphQL Input Type - CheckboxInput
 * Input fields for a single checkbox.
 *
 * @package WPGraphQL\GF\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Types\Input;

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
