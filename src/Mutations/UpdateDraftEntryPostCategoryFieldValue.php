<?php
/**
 * Mutation - updateDraftEntryPostCategoryFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry post category field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.3.0
 */

namespace WPGraphQLGravityForms\Mutations;

/**
 * Class - UpdateDraftEntryPostCategoryFieldValue
 */
class UpdateDraftEntryPostCategoryFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntryPostCategoryFieldValue';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => [ 'list_of' => 'String' ],
			'description' => __( 'The form field values. Values should be formatted as {categoryName}:{categoryId}.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Sanitizes the field value.
	 *
	 * @param array $value The field value.
	 *
	 * @return string
	 */
	protected function prepare_field_value( array $value ) : string {
		return (string) wp_json_encode( array_map( 'sanitize_text_field', $value ) );
	}
}
