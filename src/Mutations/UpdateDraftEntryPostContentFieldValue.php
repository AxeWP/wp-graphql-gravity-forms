<?php
/**
 * Mutation - updateDraftEntryPostContentFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry post content field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.3.0
 */

namespace WPGraphQLGravityForms\Mutations;

/**
 * Class - UpdateDraftEntryPostContentFieldValue
 */
class UpdateDraftEntryPostContentFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryPostContentFieldValue';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => 'String',
			'description' => __( 'The form field value.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes the field value.
	 *
	 * @param string $value The field value.
	 *
	 * @return string
	 */
	protected function prepare_field_value( string $value ) : string {
		return wp_kses_post( $value );
	}
}
