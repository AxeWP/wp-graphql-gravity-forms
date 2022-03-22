<?php
/**
 * Manipulates input data for FileUpload field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GFFormsModel;
use GF_Field_FileUpload;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FileUploadValuesInput
 */
class FileUploadValuesInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var array
	 */
	protected $args;
	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name() : string {
		return 'fileUploadValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError
	 */
	protected function prepare_value() {
		// Draft entries don't upload files.
		if ( $this->is_draft ) {
			return '';
		}

		if ( ! Utils::is_graphql_upload_enabled() ) {
			throw new UserError( __( 'To upload files you must enable the WPGraphQL Upload plugin!.', 'wp-graphql-gravity-forms' ) );
		}

		// Let people know this is a workaround until there's native WPGraphQL support.
		graphql_debug( __( 'File upload support is experimental and current relies on WPGraphQL Upload.', 'wp-graphql-gravity-forms' ) );

		$input_name = 'input_' . $this->field->id;
		// Manually move single files.
		if ( ! $this->field->multipleFiles ) {
			$this->args[0]['error'] = $this->args[0]['error'] ?? 0;
			$_FILES[ $input_name ]  = $this->args[0];
			return $this->get_single_file_value( $this->form['id'], $input_name );
		}

		// MultiUploads are handled by $_POST['gform_uploaded_files'].
		return '';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param array $field_values.
	 */
	public function add_value_to_submission( array &$field_values ) : void {
		// File uploads get their values from globals.
		$field_values[ $this->field->id ] = '';
	}

	/**
	 * Uploads and saves the single file value.
	 *
	 * Shims GF_Field_FileUpload::get_single_file_value, so we can use our custom upload file.
	 *
	 * @param int    $form_id .
	 * @param string $input_name .
	 */
	public function get_single_file_value( int $form_id, string $input_name ) : string {
		global $_gf_uploaded_files;

		// Bail early if file already uploaded.
		if ( isset( $_gf_uploaded_files[ $input_name ] ) ) {
			return '';
		}

		// Check if GF already uploaded the file.
		$file_info     = GFFormsModel::get_temp_filename( $form_id, $input_name );
		$temp_filename = rgar( $file_info, 'temp_filename', '' );
		$temp_filepath = GFFormsModel::get_upload_path( $form_id ) . '/tmp/' . $temp_filename;

		if ( $file_info && file_exists( $temp_filepath ) ) {
			// Move the temporary file to GF.
			/** @var GF_Field_FileUpload $field */
			$field = $this->field;
			$field->move_temp_file( $form_id, $file_info );
		} elseif ( ! empty( $_FILES[ $input_name ]['name'] ) ) {
			// Upload the file and store it to the global.
			$_gf_uploaded_files[ $input_name ] = $this->upload_file( $form_id, $_FILES[ $input_name ] );
		}

		return rgget( $input_name, $_gf_uploaded_files );
	}

	/**
	 * Uploads a file to to WordPress.
	 *
	 * Shim GF_Field_FileUpload::upload_file, since move_uploaded_file() only works on POSTed files.
	 *
	 * @param int   $form_id .
	 * @param array $file .
	 */
	public function upload_file( int $form_id, array $file ) : string {
		$target = GFFormsModel::get_file_upload_path( $form_id, $file['name'] );
		if ( ! $target ) {
			return 'FAILED (Upload folder could not be created.)';
		}
		if ( copy( $file['tmp_name'], $target['path'] ) ) {
			/** @var GF_Field_FileUpload $field */
			$field = $this->field;
			$field->set_permissions( $target['path'] );
			return $target['url'];
		}
		return 'FAILED (Temporary file could not be copied.)';
	}
}
