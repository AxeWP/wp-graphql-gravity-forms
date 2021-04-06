<?php
/**
 * GraphQL Input Type - FieldValuesInput
 *
 * Used to submit multiple field values.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.4.o
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;

/**
 * Class - FieldValuesInput
 */
class FieldValuesInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'FieldValuesInput';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
	}

	/**
	 * Register input type to GraphQL schema.
	 */
	public function register_input_type() : void {
		register_graphql_input_type(
			self::TYPE,
			[
				'description' => __( 'Input fields for address field.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'id'                  => [
						'type'        => [ 'non_null' => 'Int' ],
						'description' => __( 'The field id.', 'wp-graphql-gravity-forms' ),
					],
					'addressValues'       => [
						'type'        => AddressInput::TYPE,
						'description' => __( 'The form field values for Address fields.', 'wp-graphql-gravity-forms' ),
					],
					'chainedSelectValues' => [
						'type'        => [ 'list_of' => ChainedSelectInput::TYPE ],
						'description' => __( 'The form field values for ChainedSelect fields', 'wp-graphql-gravity-forms' ),
					],
					'checkboxValues'      => [
						'type'        => [ 'list_of' => CheckboxInput::TYPE ],
						'description' => __( 'The form field values for Checkbox fields', 'wp-graphql-gravity-forms' ),
					],
					'listValues'          => [
						'type'        => [ 'list_of' => ListInput::TYPE ],
						'description' => __( 'The form field values for List fields', 'wp-graphql-gravity-forms' ),
					],
					'nameValues'          => [
						'type'        => NameInput::TYPE,
						'description' => __( 'The form field values for Name fields', 'wp-graphql-gravity-forms' ),
					],
					'values'              => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'The form field values for fields that accept multiple string values. Used by MultiSelect, Post Category, Post Custom, and Post Tags fields.', 'wp-graphql-gravity-forms' ),
					],
					'value'               => [
						'type'        => 'String',
						'description' => __( 'The form field values for basic fields', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
