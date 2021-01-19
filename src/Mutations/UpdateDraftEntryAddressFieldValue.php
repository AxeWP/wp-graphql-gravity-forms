<?php

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\AddressInput;

/**
 * Update a Gravity Forms draft entry address field value.
 */
class UpdateDraftEntryAddressFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryAddressFieldValue';

	/**
	 * @return array The input field value.
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => AddressInput::TYPE,
			'description' => __( 'The form field value.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * @param string The field value.
	 *
	 * @return array The sanitized field value.
	 */
	protected function prepare_field_value( array $value ) : array {
		return [
			$this->field['inputs'][0]['id'] => array_key_exists( 'street', $value ) ? sanitize_text_field( $value['street'] ) : null,
			$this->field['inputs'][1]['id']  => array_key_exists( 'lineTwo', $value ) ? sanitize_text_field( $value['lineTwo'] ) : null,
			$this->field['inputs'][2]['id'] => array_key_exists( 'city', $value ) ? sanitize_text_field( $value['city'] ) : null,
			$this->field['inputs'][3]['id']  => array_key_exists( 'state', $value ) ? sanitize_text_field( $value['state'] ) : null,
			$this->field['inputs'][4]['id']  => array_key_exists( 'zip', $value ) ? sanitize_text_field( $value['zip'] ) : null,
			$this->field['inputs'][5]['id']  => array_key_exists( 'country', $value ) ? sanitize_text_field( $value['country'] ) : null,
		];
	}
}
