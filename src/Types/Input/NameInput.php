<?php
/**
 * GraphQL Input Type - NameInput
 * Input fields for name field.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

/**
 * Class - NameInput
 */
class NameInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'NameInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Input fields for name field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
		return [
			'prefix' => [
				'type'        => 'String',
				'description' => __( 'Prefix, such as Mr., Mrs. etc.', 'wp-graphql-gravity-forms' ),
			],
			'first'  => [
				'type'        => 'String',
				'description' => __( 'First name.', 'wp-graphql-gravity-forms' ),
			],
			'middle' => [
				'type'        => 'String',
				'description' => __( 'Middle name.', 'wp-graphql-gravity-forms' ),
			],
			'last'   => [
				'type'        => 'String',
				'description' => __( 'Last name.', 'wp-graphql-gravity-forms' ),
			],
			'suffix' => [
				'type'        => 'String',
				'description' => __( 'Suffix, such as Sr., Jr. etc.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
