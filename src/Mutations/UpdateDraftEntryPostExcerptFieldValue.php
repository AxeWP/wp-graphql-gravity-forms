<?php
/**
 * Mutation - updateDraftEntryPostExcerptFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry post excerpt field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.3.0
 */

namespace WPGraphQLGravityForms\Mutations;

/**
 * Class - UpdateDraftEntryPostExcerptFieldValue
 */
class UpdateDraftEntryPostExcerptFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntryPostExcerptFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'post_excerpt';

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
		return $this->prepare_string_value( $value );
	}
}
