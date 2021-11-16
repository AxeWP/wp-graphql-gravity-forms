<?php
/**
 * GraphQL Input Type - FieldValuesInput
 *
 * Used to submit multiple field values.
 *
 * @package WPGraphQL\GF\Types\Input
 * @since   0.4.o
 */

namespace WPGraphQL\GF\Types\Input;

/**
 * Class - FieldValuesInput
 */
class FieldValuesInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FieldValuesInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Field values input. Includes a field id, and a valid value Input.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		$fields = [
			'id'             => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'The field id.', 'wp-graphql-gravity-forms' ),
			],
			'addressValues'  => [
				'type'        => AddressInput::$type,
				'description' => __( 'The form field values for Address fields.', 'wp-graphql-gravity-forms' ),
			],
			'checkboxValues' => [
				'type'        => [ 'list_of' => CheckboxInput::$type ],
				'description' => __( 'The form field values for Checkbox fields', 'wp-graphql-gravity-forms' ),
			],
			'emailValues'    => [
				'type'        => EmailInput::$type,
				'description' => __( 'The form field values for Email fields.', 'wp-graphql-gravity-forms' ),
			],
			'listValues'     => [
				'type'        => [ 'list_of' => ListInput::$type ],
				'description' => __( 'The form field values for List fields', 'wp-graphql-gravity-forms' ),
			],
			'nameValues'     => [
				'type'        => NameInput::$type,
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
				'type'        => [ 'list_of' => ChainedSelectInput::$type ],
				'description' => __( 'The form field values for ChainedSelect fields', 'wp-graphql-gravity-forms' ),
			];
		}

		if ( class_exists( 'WPGraphQL\Upload\Type\Upload' ) ) {
			$fields['fileUploadValues'] = [
				'type'        => [ 'list_of' => 'Upload' ],
				'description' => __( 'The form field values for file upload fields.', 'wp-graphql-gravity-forms' ),
			];
			$fields['postImageValues']  = [
				'type'        => PostImageInput::$type,
				'description' => __( 'The form field values for post image fields.', 'wp-graphql-gravity-forms' ),
			];
		}

		ksort( $fields );

		return $fields;
	}
}
