<?php

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\ListInput;


/**
 * Update a Gravity Forms draft entry with a List value.
 */
class UpdateDraftEntryListFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryListFieldValue';

	/**
	 * @return array The input field value.
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => ListInput::TYPE ],
			'description' => __( 'The form field values.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * @param array The field values.
	 *
	 * @return string Sanitized and JSON encoded field values.
	 */
	protected function prepare_field_value( array $value ) : string {
		$values_to_save = array_map( function( $row ) {
			$row_values = []; // Initializes array.

			// If columns are enabled, save each choice => value pair.
			if( $this->field->enableColumns){

				foreach( $this->field->choices as $choice_key => $choice ){
					$row_values[] = [
						$choice['value'] => isset($row['values'][$choice_key]) ? sanitize_text_field($row['values'][$choice_key]) : null,
					];
				}

				return $row_values;
			}

			// If no columns, values can be saved directly to the array.
			return isset( $row['values'][0]) ? sanitize_text_field($row['values'][0]) : null;
		}, $value);

		return (string) serialize( $values_to_save );
	}
}
