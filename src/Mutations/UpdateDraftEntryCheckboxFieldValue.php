<?php

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\CheckboxInput;

/**
 * Update a Gravity Forms draft entry checkbox field value.
 */
class UpdateDraftEntryCheckboxFieldValue extends DraftEntryUpdater {
    /**
     * Mutation name.
     */
	const NAME = 'updateDraftEntryCheckboxFieldValue';

	/**
     * @return array The input field value.
     */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => CheckboxInput::TYPE ],
			'description' => __( 'Checkbox input values.', 'wp-graphql-gravity-forms' ),
		];
	}

    /**
     * @param array $value The field value.
     *
     * @return array Field value to save.
     */
	protected function prepare_field_value( array $value ) : array {
		$values_to_save = array_reduce( $this->field->inputs, function( array $values_to_save, array $input ) : array {
			$values_to_save[ $input['id'] ] = ''; // Initialize all inputs to an empty string.
			return $values_to_save;
		}, [] );

		foreach ( $value as $single_value ) {
			$input_id    = sanitize_text_field( $single_value['inputId'] );
			$input_value = sanitize_text_field( $single_value['value'] );

			// Make sure the input ID passed in exists.
			if ( ! isset( $values_to_save[ $input_id ] ) ) {
				continue;
			}

			// Overwrite initial empty string with the value passed in.
			$values_to_save[ $input_id ] = $input_value;
		}

		return $values_to_save;
	}
}
