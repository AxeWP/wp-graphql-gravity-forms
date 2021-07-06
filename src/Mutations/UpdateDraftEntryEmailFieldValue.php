<?php
/**
 * Mutation - updateDraftEntryEmailFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry email field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\EmailInput;

/**
 * Class - UpdateDraftEntryEmailFieldValue
 */
class UpdateDraftEntryEmailFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryEmailFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'email';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => EmailInput::$type,
			'description' => __( 'The form field value.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes the field values.
	 *
	 * @param array $value The field value.
	 *
	 * @return array
	 */
	protected function prepare_field_value( array $value ) : array {
		return $this->prepare_email_field_value( $value, $this->field );
	}
}
