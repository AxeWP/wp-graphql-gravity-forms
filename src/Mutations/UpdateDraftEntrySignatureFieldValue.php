<?php
/**
 * Mutation - updateDraftEntrySignatureFieldValue
 *
 * Registers mutation to update a Gravity Forms draft entry signature field value.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 * @since 0.3.0 use $this->field['id'] to correctly delete signature image.
 */

namespace WPGraphQLGravityForms\Mutations;

/**
 * Class - UpdateDraftEntrySignatureFieldValue
 */
class UpdateDraftEntrySignatureFieldValue extends AbstractDraftEntryUpdater {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateDraftEntrySignatureFieldValue';

	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type = 'signature';

	/**
	 * Defines the input field value configuration.
	 *
	 * @return array
	 */
	protected function get_value_input_field() : array {
		return [
			'type'        => 'String',
			'description' => __( 'The signature as a base-64 encoded data URL with a MIME type of image/png.', 'wp-graphql-gravity-forms' ),
		];
	}

	/**
	 * Saves field value.
	 *
	 * @param string $value Base-64 encoded png signature image value.
	 *
	 * @return string The filename of the saved signature image file.
	 */
	protected function prepare_field_value( string $value ) : string {
		return $this->prepare_signature_field_value( $value, $this->submission['partial_entry'][ $this->field['id'] ] );
	}

}
