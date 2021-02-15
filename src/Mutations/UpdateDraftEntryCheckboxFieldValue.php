<?php
/**
 * Mutation - updateDraftEntryCheckboxFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry checkbox field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\CheckboxInput;

/**
 * Class - UpdateDraftEntryCheckboxFieldValue
 */
class UpdateDraftEntryCheckboxFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryCheckboxFieldValue';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => CheckboxInput::TYPE ],
			'description' => __( 'Checkbox input values.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes the checkbox field values.
	 *
	 * @param array $value The field value.
	 *
	 * @return array
	 */
	protected function prepare_field_value( array $value ) : array {
		$values_to_save = array_reduce(
			$this->field->inputs,
			function( array $values_to_save, array $input ) : array {
				$values_to_save[ $input['id'] ] = ''; // Initialize all inputs to an empty string.
				return $values_to_save;
			},
			[]
		);

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
