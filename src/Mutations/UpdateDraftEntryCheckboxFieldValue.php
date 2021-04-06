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
class UpdateDraftEntryCheckboxFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryCheckboxFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'checkbox';

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
		return $this->prepare_complex_field_value( $value, $this->field );
	}
}
