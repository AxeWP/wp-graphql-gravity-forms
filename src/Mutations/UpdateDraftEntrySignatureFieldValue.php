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

use GFCommon;
use GFSignature;
use GraphQL\Error\UserError;

/**
 * Class - UpdateDraftEntrySignatureFieldValue
 */
class UpdateDraftEntrySignatureFieldValue extends DraftEntryUpdater {
	/**
	 * Mutation name.
	 */
	const NAME = 'updateDraftEntrySignatureFieldValue';

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
		$this->ensure_signature_plugin_is_active();
		$this->ensure_signatures_folder_exists();
		$this->delete_previous_signature_image();

		return $this->save_signature( $value );
	}

	/**
	 * Checks if the Gravity Forms Signature Add-On plugin is active.
	 *
	 * @throws UserError .
	 */
	private function ensure_signature_plugin_is_active() {
		if ( ! class_exists( 'GFSignature' ) ) {
			throw new UserError( __( 'The Gravity Forms Signature Add-On plugin must be active for signature field values to be saved.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Ensures the folder for storing signatures exists.
	 *
	 * @throws UserError .
	 */
	private function ensure_signatures_folder_exists() {
		$folder = GFSignature::get_signatures_folder();
		$exists = wp_mkdir_p( $folder );

		if ( ! $exists ) {
			throw new UserError( __( 'The Gravity Forms Signatures directory could not be created.', 'wp-graphql-gravity-forms' ) );
		}

		// Add index.html to prevent directory browsing.
		GFCommon::recursive_add_index_file( $folder );
	}

	/**
	 * Deletes previous signature image.
	 */
	private function delete_previous_signature_image() {
		$prev_filename = $this->submission['partial_entry'][ $this->field['id'] ] ?? '';

		if ( ! $prev_filename ) {
			return;
		}

		$folder = GFSignature::get_signatures_folder();
		$path   = $folder . $prev_filename;

		if ( file_exists( $path ) ) {
			unlink( $path );
		}
	}

	/**
	 * Replacement for the the GFSignature::save_signature() method.
	 *
	 * @param string $signature Base-64 encoded png signature image data.
	 *
	 * @return string $filename The filename of the saved signature image file.
	 *
	 * @throws UserError .
	 */
	private function save_signature( string $signature ) : string {
		if ( '' === $signature ) {
			return '';
		}

		$signature_decoded = $this->get_decoded_image_data( $signature );

		if ( $this->does_image_exceed_max_upload_size( $signature_decoded ) ) {
			throw new UserError( __( 'The signature image exceeds the maximum upload file size allowed.', 'wp-graphql-gravity-forms' ) );
		}

		$folder          = GFSignature::get_signatures_folder();
		$filename        = uniqid( '', true ) . '.png';
		$path            = $folder . $filename;
		$number_of_bytes = file_put_contents( $path, $signature_decoded );

		if ( false === $number_of_bytes ) {
			throw new UserError( __( 'An error occurred while saving the signature image.', 'wp-graphql-gravity-forms' ) );
		}

		return $filename;
	}

	/**
	 * Decodes base-64 encoded png signature image data.
	 *
	 * @param string $signature Base-64 encoded png signature image data.
	 *
	 * @return string
	 * @throws UserError .
	 */
	private function get_decoded_image_data( string $signature ) : string {
		$string_parts = explode( ';', $signature );

		if ( 2 !== count( $string_parts ) || 'data:image/png' !== $string_parts[0] ) {
			throw new UserError( __( 'An invalid signature image was provided. Image must be a base-64 encoded png.', 'wp-graphql-gravity-forms' ) );
		}

		$data_parts = explode( ',', $string_parts[1] );

		if ( 2 !== count( $data_parts ) || 'base64' !== $data_parts[0] || ! $data_parts[1] ) {
			throw new UserError( __( 'An invalid signature image was provided. Image must be a base-64 encoded png.', 'wp-graphql-gravity-forms' ) );
		}

		$image_data_decoded = base64_decode( $data_parts[1] ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

		if ( ! $image_data_decoded ) {
			throw new UserError( __( 'An invalid signature image was provided. Image must be a base-64 encoded png.', 'wp-graphql-gravity-forms' ) );
		}

		return $image_data_decoded;
	}

	/**
	 * Checks whether the png signature image exceeds the server's max upload size.
	 *
	 * @param string $signature_decoded Decoded png signature image data.
	 *
	 * @return bool
	 */
	private function does_image_exceed_max_upload_size( string $signature_decoded ) : bool {
		return strlen( $signature_decoded ) > wp_max_upload_size();
	}
}
