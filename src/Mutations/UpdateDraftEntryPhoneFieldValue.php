<?php

namespace WPGraphQLGravityForms\Mutations;

/**
 * Update a Gravity Forms draft entry phone field value.
 */
class UpdateDraftEntryPhoneFieldValue extends DraftEntryUpdater {
    /**
     * Mutation name.
     */
	const NAME = 'updateDraftEntryPhoneFieldValue';

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
	protected function prepare_field_value( string $value ) : string {
		return sanitize_text_field( $value );
	}
}
