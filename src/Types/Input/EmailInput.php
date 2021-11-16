<?php
/**
 * GraphQL Input Type - EmailInput
 * Input fields for a single checkbox.
 *
 * @package WPGraphQL\GF\Types\Input
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Types\Input;

/**
 * Class - EmailInput
 */
class EmailInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EmailInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Input fields for email field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return [
			'value'             => [
				'type'        => 'String',
				'description' => __( 'Email input value', 'wp-graphql-gravity-forms' ),
			],
			'confirmationValue' => [
				'type'        => 'String',
				'description' => __( 'Email confirmation input value. Only used when email confirmation is enabled.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
