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
class UpdateDraftEntryConsentFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryConsentFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'consent';

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
		return $this->prepare_consent_field_value( $value, $this->field );
	}
}
