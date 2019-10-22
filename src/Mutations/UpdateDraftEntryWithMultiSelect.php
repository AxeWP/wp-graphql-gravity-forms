<?php

namespace WPGraphQLGravityForms\Mutations;

/**
 * Update a Gravity Forms draft entry with a multi-select value.
 */
class UpdateDraftEntryWithMultiSelect extends DraftEntryUpdater {
    /**
     * Mutation name.
     */
	const NAME = 'updateDraftEntryWithMultiSelect';

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
     * @return array The sanitized field values.
     */
	protected function sanitize_field_value( $value ) {
		return array_map( 'sanitize_text_field', $value );
	}
}
