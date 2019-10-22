<?php

namespace WPGraphQLGravityForms\Mutations;

/**
 * Update a Gravity Forms draft entry with a string value.
 */
class UpdateDraftEntryWithString extends DraftEntryUpdater {
    /**
     * Mutation name.
     */
	const NAME = 'updateGravityFormsDraftEntryWithString';

	/**
     * @return array The input field value.
     */
	protected function get_value_input_field() : array {
		return [
			'type'        => 'String',
			'description' => __( 'The form field value.', 'wp-graphql-gravity-forms' ),
		];
	}

    /**
     * @param string The field value.
     *
     * @return string The sanitized field value.
     */
	protected function sanitize_field_value( $value ) {
		return sanitize_text_field( $value );
	}
}
