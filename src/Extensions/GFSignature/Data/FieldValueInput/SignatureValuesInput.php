<?php
/**
 * Manipulates input data for Signature field values.
 *
 * @package WPGraphQL\GF\Extensions\GFSignature\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Data\FieldValueInput;

use GFCommon;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Data\FieldValueInput\ValueInput;

/**
 * Class - SignatureValuesInput
 */
class SignatureValuesInput extends ValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 */
	protected function prepare_value(): string {
		$value = $this->args;

		$this->ensure_signatures_folder_exists();

		$filename = $this->save_signature( $value );

		if ( ! empty( $this->entry[ $this->field->id ] ) ) {
			$this->delete_previous_signature_image( $this->entry[ $this->field->id ] );
		}

		// Save values to $_POST for GF validation.
		if ( ! empty( $filename ) ) {
			$_POST[ 'input_' . $this->field->formId . '_' . $this->field->id . '_signature_filename' ] = $filename;
			$_POST[ 'input_' . $this->field->formId . '_' . $this->field->id . '_valid' ]              = true;
		}

		return $filename;
	}

	/**
	 * Ensures the folder for storing signatures exists.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	protected function ensure_signatures_folder_exists(): void {
		$folder = \GFSignature::get_signatures_folder();
		$exists = wp_mkdir_p( $folder );

		if ( ! $exists ) {
			throw new UserError( __( 'The Gravity Forms Signatures directory could not be created.', 'wp-graphql-gravity-forms' ) );
		}

		// Add index.html to prevent directory browsing.
		GFCommon::recursive_add_index_file( $folder );
	}

	/**
	 * Replacement for the the GFSignature::save_signature() method.
	 *
	 * @param string $signature Base-64 encoded png signature image data.
	 *
	 * @return string $filename The filename of the saved signature image file.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	protected function save_signature( string $signature ): string {
		if ( '' === $signature ) {
			return '';
		}

		$signature_decoded = $this->get_decoded_image_data( $signature );

		if ( $this->does_image_exceed_max_upload_size( $signature_decoded ) ) {
			throw new UserError( __( 'The signature image exceeds the maximum upload file size allowed.', 'wp-graphql-gravity-forms' ) );
		}

		$folder   = \GFSignature::get_signatures_folder();
		$filename = uniqid( '', true ) . '.png';
		$path     = $folder . $filename;
		// @todo: switch to WP Filesystem.
		$number_of_bytes = file_put_contents( $path, $signature_decoded ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents

		if ( false === $number_of_bytes ) {
			throw new UserError( __( 'An error occurred while saving the signature image.', 'wp-graphql-gravity-forms' ) );
		}

		return $filename;
	}

	/**
	 * Deletes the previous signature image
	 *
	 * @param string $prev_filename The file name of the image to delete.
	 */
	protected function delete_previous_signature_image( string $prev_filename = null ): void {
		if ( ! $prev_filename ) {
			return;
		}

		$folder = \GFSignature::get_signatures_folder();
		$path   = $folder . $prev_filename;

		if ( file_exists( $path ) ) {
			unlink( $path ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
		}
	}

	/**
	 * Checks whether the png signature image exceeds the server's max upload size.
	 *
	 * @param string $signature_decoded Decoded png signature image data.
	 */
	protected function does_image_exceed_max_upload_size( string $signature_decoded ): bool {
		return strlen( $signature_decoded ) > wp_max_upload_size();
	}

	/**
	 * Decodes base-64 encoded png signature image data.
	 *
	 * @param string $signature Base-64 encoded png signature image data.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	protected function get_decoded_image_data( string $signature ): string {
		$error_message = __( 'An invalid signature image was provided. Image must be a base-64 encoded png.', 'wp-graphql-gravity-forms' );

		$string_parts = explode( ';', $signature );

		if ( 2 !== count( $string_parts ) || 'data:image/png' !== $string_parts[0] ) {
			throw new UserError( $error_message );
		}

		$data_parts = explode( ',', $string_parts[1] );

		if ( 2 !== count( $data_parts ) || 'base64' !== $data_parts[0] || ! $data_parts[1] ) {
			throw new UserError( $error_message );
		}

		$image_data_decoded = base64_decode( $data_parts[1] ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

		if ( ! $image_data_decoded ) {
			throw new UserError( $error_message );
		}

		return $image_data_decoded;
	}
}
