<?php
/**
 * Mutation - updateDraftEntryListFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry list field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\ListInput;


/**
 * Class - UpdateDraftEntryListFieldValue
 */
class UpdateDraftEntryListFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryListFieldValue';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => ListInput::TYPE ],
			'description' => __( 'The form field values.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes and serialize the field values.
	 *
	 * @param array $value The field values.
	 *
	 * @return string
	 */
	protected function prepare_field_value( array $value ) : string {
		$values_to_save = array_map(
			function( $row ) {
				$row_values = []; // Initializes array.

				// If columns are enabled, save each choice => value pair.
				if ( $this->field->enableColumns ) {
					foreach ( $this->field->choices as $choice_key => $choice ) {
						$row_values[] = [
							$choice['value'] => isset( $row['values'][ $choice_key ] ) ? sanitize_text_field( $row['values'][ $choice_key ] ) : null,
						];
					}

					return $row_values;
				}

				// If no columns, values can be saved directly to the array.
				return isset( $row['values'][0] ) ? sanitize_text_field( $row['values'][0] ) : null;
			},
			$value
		);

		return (string) serialize( $values_to_save );
	}
}
