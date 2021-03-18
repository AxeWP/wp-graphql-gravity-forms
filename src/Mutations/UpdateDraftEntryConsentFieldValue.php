<?php
/**
 * Mutation - updateDraftEntryConsentFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry Consent field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.3.0
 */

namespace WPGraphQLGravityForms\Mutations;

/**
 * Class - UpdateDraftEntryConsentFieldValue
 */
class UpdateDraftEntryConsentFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryConsentFieldValue';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => 'Boolean',
			'description' => __( 'Consent input values.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes the Consent field values.
	 *
	 * @param bool $value The field value.
	 *
	 * @return array
	 */
	protected function prepare_field_value( bool $value ) : array {
		return [
			$this->field->inputs[0]['id'] => (bool) $value,
			$this->field->inputs[1]['id'] => isset( $this->field->checkboxLabel ) ? sanitize_text_field( $this->field->checkboxLabel ) : null,
			$this->field->inputs[2]['id'] => isset( $this->field->descriptiom ) ? sanitize_text_field( $this->field->description ) : null,
		];
	}
}
