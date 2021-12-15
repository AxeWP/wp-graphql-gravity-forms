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
	public static function get_description() : string {
		return __( 'Field values input. Includes a field id, and a valid value Input.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
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
				'description' => __( 'The form field values for Checkbox fields', 'wp-graphql-gravity-forms' ),
			],
			'emailValues'    => [
				'type'        => EmailFieldInput::$type,
				'description' => __( 'The form field values for Email fields.', 'wp-graphql-gravity-forms' ),
			],
			'listValues'     => [
				'type'        => [ 'list_of' => ListFieldInput::$type ],
				'description' => __( 'The form field values for List fields', 'wp-graphql-gravity-forms' ),
			],
			'nameValues'     => [
				'type'        => NameFieldInput::$type,
				'description' => __( 'The form field values for Name fields', 'wp-graphql-gravity-forms' ),
			],
			'values'         => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'The form field values for fields that accept multiple string values. Used by MultiSelect, Post Category, Post Custom, and Post Tags fields.', 'wp-graphql-gravity-forms' ),
			],
			'value'          => [
				'type'        => 'String',
				'description' => __( 'The form field values for basic fields', 'wp-graphql-gravity-forms' ),
			],
		];

		if ( class_exists( 'GFChainedSelects' ) ) {
			$fields['chainedSelectValues'] = [
				'type'        => [ 'list_of' => ChainedSelectFieldInput::$type ],
				'description' => __( 'The form field values for ChainedSelect fields', 'wp-graphql-gravity-forms' ),
			];
		}

		if ( class_exists( 'WPGraphQL\Upload\Type\Upload' ) ) {
			$fields['fileUploadValues'] = [
				'type'        => [ 'list_of' => 'Upload' ],
				'description' => __( 'The form field values for file upload fields.', 'wp-graphql-gravity-forms' ),
			];
			$fields['postImageValues']  = [
				'type'        => PostImageFieldInput::$type,
				'description' => __( 'The form field values for post image fields.', 'wp-graphql-gravity-forms' ),
			];
		}

		ksort( $fields );

		return $fields;
	}
}
