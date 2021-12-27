<?php
/**
 * Manipulates input data for String field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Data\FieldValueInput;

use GFFormsModel;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\GFUtils;
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
	protected $input_value;
	/**
	 * {@inheritDoc}
	 */
	public function get_value_key() : string {
		return 'fileUploadValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError
	 */
	protected function prepare_value() {
		if ( ! class_exists( 'WPGraphQL\Upload\Type\Upload' ) ) {
			throw new UserError( __( 'To upload files you must enable the WPGraphQL Upload plugin!.', 'wp-graphql-gravity-forms' ) );
		}

		// Let people know this is a workaround until there's native WPGraphQL support.
		graphql_debug( __( 'File upload support is experimental, and current relies on WPGraphQL Upload.', 'wp-graphql-gravity-forms' ) );

		$target = GFUtils::get_gravity_forms_upload_dir( $this->form['id'] );

		// Gravity Forms uses $_gf_uploaded_files to store and validate multipleFile uploads.
		global $_gf_uploaded_files;

		$input_name = 'input_' . $this->field->id;

		if ( empty( $_gf_uploaded_files ) ) {
			$_gf_uploaded_files = [];
		}

		$files = [];
		$urls  = [];
		foreach ( $this->input_value as $single_value ) {
			if ( ! array_key_exists( 'error', $single_value ) || empty( $single_value['error'] ) ) {
				$single_value['error'] = 0;
			}

			if ( ! $this->field->multipleFiles ) {
				$_FILES[ $input_name ] = $single_value;

				if ( ! empty( $_gf_uploaded_files[ $input_name ] ) ) {
					return $_gf_uploaded_files[ $input_name ];
				}
			}
			$uploaded_file = GFUtils::handle_file_upload( $single_value, $target );

			// Set values needed for validation.
			if ( ! $this->field->multipleFiles ) {
				$_gf_uploaded_files[ $input_name ] = $uploaded_file['url'];

				GFFormsModel::$uploaded_files[ $this->field->formId ][ $input_name ] = $_gf_uploaded_files[ $input_name ];

				return $_gf_uploaded_files[ $input_name ];
			}

			$files[] = [
				'temp_filename'     => $single_value['tmp_name'],
				'uploaded_filename' => $single_value['name'],
			];
			$urls[]  = $uploaded_file['url'];
		}

		if ( ! empty( $this->entry[ $this->field->id ] ) ) {
			$this->delete_previous_files( $this->entry[ $this->field->id ] );
		}

		$_gf_uploaded_files[ $input_name ] = wp_json_encode( array_values( $urls ) );

		if ( ! $_gf_uploaded_files[ $input_name ] ) {
			throw new UserError( __( 'Mutation failed. File paths couldnt be encoded as JSON.', 'wp-graphql-gravity-forms' ) );
		}

		GFFormsModel::$uploaded_files[ $this->field->formId ][ $input_name ] = $files;

		return $_gf_uploaded_files[ $input_name ];
	}

	/**
	 * Copy of GFFormsModel::delete_physical_file.
	 *
	 * @param string $prev_url .
	 */
	protected function delete_previous_files( $prev_url = null ) : void {
		if ( ! $prev_url ) {
			return;
		}

		// Create array of urls for deletion loop.
		$files_to_delete = Utils::maybe_decode_json( $prev_url );

		if ( false === $files_to_delete ) {
			return;
		}

		foreach ( $files_to_delete as $file ) {
			$ary = explode( '|:|', $file );
			$url = $ary[0];

			if ( empty( $url ) ) {
				continue;
			}

			$file_path = GFFormsModel::get_physical_file_path( $url );
			/**
			 * Allow the file path to be overridden so files stored outside the /wp-content/uploads/gravity_forms/ directory can be deleted.
			 */
			$file_path = apply_filters( 'gform_file_path_pre_delete_file', $file_path, $url );

			if ( file_exists( $file_path ) ) {
				unlink( $file_path );
			}
		}
	}
}
