<?php
/**
 * Mutation - updateDraftEntryChainedSelectFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry ChainedSelect field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.3.0
 */

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\ChainedSelectInput;

/**
 * Class - UpdateDraftEntryChainedSelectFieldValue
 */
class UpdateDraftEntryChainedSelectFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryChainedSelectFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'chainedselect';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => ChainedSelectInput::TYPE ],
			'description' => __( 'ChainedSelect input values.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes the ChainedSelect field values.
	 *
	 * @param array $value The field value.
	 *
	 * @return array
	 */
	protected function prepare_field_value( array $value ) : array {
		return $this->prepare_complex_field_value( $value, $this->field );
	}
}
