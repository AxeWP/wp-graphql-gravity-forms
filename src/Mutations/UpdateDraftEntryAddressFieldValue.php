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
class UpdateDraftEntryAddressFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryAddressFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'address';

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
		return $this->prepare_address_field_value( $value, $this->field );
	}
}
