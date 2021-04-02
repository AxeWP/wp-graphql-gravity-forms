<?php
/**
 * Mutation - updateDraftEntryMultiSelectFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry multi-select field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Mutations;

/**
 * Class - UpdateDraftEntryMultiSelectFieldValue
 */
class UpdateDraftEntryMultiSelectFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryMultiSelectFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'multiselect';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => 'String' ],
			'description' => __( 'The form field values.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes and JSON encode the field values.
	 *
	 * @param array $value The field values.
	 *
	 * @return array
	 */
	protected function prepare_field_value( array $value ) : array {
		return $this->prepare_string_array_value( $value );
	}
}
