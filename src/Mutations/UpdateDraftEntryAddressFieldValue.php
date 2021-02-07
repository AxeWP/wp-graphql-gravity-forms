<?php
/**
 * Mutation - updateDraftEntryAddressFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry address field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\AddressInput;

/**
 * Class - UpdateDraftEntryAddressFieldValue
 */
class UpdateDraftEntryAddressFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryAddressFieldValue';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => AddressInput::TYPE,
			'description' => __( 'The form field value.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes the address field values.
	 *
	 * @param array $value The field value.
	 *
	 * @return array
	 */
	protected function prepare_field_value( array $value ) : array {
		return [
			$this->field['inputs'][0]['id'] => array_key_exists( 'street', $value ) ? sanitize_text_field( $value['street'] ) : null,
			$this->field['inputs'][1]['id'] => array_key_exists( 'lineTwo', $value ) ? sanitize_text_field( $value['lineTwo'] ) : null,
			$this->field['inputs'][2]['id'] => array_key_exists( 'city', $value ) ? sanitize_text_field( $value['city'] ) : null,
			$this->field['inputs'][3]['id'] => array_key_exists( 'state', $value ) ? sanitize_text_field( $value['state'] ) : null,
			$this->field['inputs'][4]['id'] => array_key_exists( 'zip', $value ) ? sanitize_text_field( $value['zip'] ) : null,
			$this->field['inputs'][5]['id'] => array_key_exists( 'country', $value ) ? sanitize_text_field( $value['country'] ) : null,
		];
	}
}
