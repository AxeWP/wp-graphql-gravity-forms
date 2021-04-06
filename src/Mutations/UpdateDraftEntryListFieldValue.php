<?php
/**
 * Mutation - updateDraftEntryListFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry list field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 * @since 0.3.0 Deprecate `values` in favor of `rowValues`.
 */

namespace WPGraphQLGravityForms\Mutations;

use WPGraphQLGravityForms\Types\Input\ListInput;


/**
 * Class - UpdateDraftEntryListFieldValue
 */
class UpdateDraftEntryListFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryListFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'list';

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
	 * @return array
	 */
	protected function prepare_field_value( array $value ) : array {
		return $this->prepare_list_field_value( $value );
	}
}
