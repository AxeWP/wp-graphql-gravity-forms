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
class UpdateDraftEntryMultiSelectFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryMultiSelectFieldValue';

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
	 * @return string
	 */
	protected function prepare_field_value( array $value ) : string {
		return (string) json_encode( array_map( 'sanitize_text_field', $value ) );
	}
}
