<?php

namespace WPGraphQLGravityForms\Mutations;

/**
 * Update a Gravity Forms draft entry with an integer value.
 */
class UpdateDraftEntryWithInt extends DraftEntryUpdater {
    /**
     * Mutation name.
     */
	const NAME = 'updateGravityFormsDraftEntryWithInt';

	/**
     * @return array The input field value.
     */
	protected function get_value_input_field() : array {
		return [
			'type'        => 'Integer',
			'description' => __( 'The form field value.', 'wp-graphql-gravity-forms' ),
		];
	}

    /**
     * @param string The field value.
     *
     * @return string The sanitized field value.
     */
	protected function sanitize_field_value( $value ) {
		return (int) $value;
	}
}
