<?php
/**
 * GraphQL Input Type - FormFieldValuesInput
 *
 * Used to submit multiple field values.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.4.o
 */

namespace WPGraphQL\GF\Type\Input;

use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FormFieldValuesInput
 */
class FormFieldValuesInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldValuesInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Field values input. Includes a field id, and a valid value Input.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		$fields = [
			'id'             => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'The field id.', 'wp-graphql-gravity-forms' ),
			],
			'addressValues'  => [
				'type'        => AddressFieldInput::$type,
				'description' => __( 'The form field values for Address fields.', 'wp-graphql-gravity-forms' ),
			],
			'checkboxValues' => [
				'type'        => [ 'list_of' => CheckboxFieldInput::$type ],
				'description' => __( 'The form field values for Checkbox fields.', 'wp-graphql-gravity-forms' ),
			],
			'emailValues'    => [
				'type'        => EmailFieldInput::$type,
				'description' => __( 'The form field values for Email fields.', 'wp-graphql-gravity-forms' ),
			],
			'listValues'     => [
				'type'        => [ 'list_of' => ListFieldInput::$type ],
				'description' => __( 'The form field values for List fields.', 'wp-graphql-gravity-forms' ),
			],
			'nameValues'     => [
				'type'        => NameFieldInput::$type,
				'description' => __( 'The form field values for Name fields.', 'wp-graphql-gravity-forms' ),
			],
			'productValues'  => [
				'type'        => ProductFieldInput::$type,
				'description' => __( 'The form field values for Name fields.', 'wp-graphql-gravity-forms' ),
			],
			'values'         => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'The form field values for fields that accept multiple string values. Used by MultiSelect, Post Category, Post Custom, and Post Tags fields.', 'wp-graphql-gravity-forms' ),
			],
			'value'          => [
				'type'        => 'String',
				'description' => __( 'The form field values for basic fields.', 'wp-graphql-gravity-forms' ),
			],
		];

		if ( Utils::is_graphql_upload_enabled() ) {
			$fields['fileUploadValues'] = [
				'type'        => [ 'list_of' => 'Upload' ],
				'description' => __( 'The form field values for file upload fields.', 'wp-graphql-gravity-forms' ),
			];
			$fields['imageValues']      = [
				'type'        => PostImageFieldInput::$type,
				'description' => __( 'The form field values for post image fields.', 'wp-graphql-gravity-forms' ),
			];
		}

		/**
		 * Filters the possible input fields for the FormFieldValuesInput GraphQL type.
		 *
		 * Useful for adding support for custom form fields.
		 *
		 * @param array $fields The registered input fields.
		 */
		$fields = apply_filters( 'graphql_gf_form_field_values_input_fields', $fields );

		ksort( $fields );

		return $fields;
	}
}
