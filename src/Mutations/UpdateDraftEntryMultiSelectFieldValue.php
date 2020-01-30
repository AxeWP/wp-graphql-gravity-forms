<?php

namespace WPGraphQLGravityForms\Mutations;

/**
 * Update a Gravity Forms draft entry with a multi-select value.
 */
class UpdateDraftEntryMultiSelectFieldValue extends DraftEntryUpdater {
    /**
     * Mutation name.
     */
	const NAME = 'updateDraftEntryMultiSelectFieldValue';

	/**
     * @return array The input field value.
     */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => 'String' ],
			'description' => __( 'The form field values.', 'wp-graphql-gravity-forms' ),
		];
	}

    /**
     * @param array The field values.
     *
     * @return string Sanitized and JSON encoded field values.
     */
	protected function prepare_field_value( array $value ) : string {
		return (string) json_encode( array_map( 'sanitize_text_field', $value ) );
	}
}
