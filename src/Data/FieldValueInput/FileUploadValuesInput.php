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

		// Gravity Forms uses $_gf_uploaded_files to store and validate multipleFile uploads.
		global $_gf_uploaded_files;

		$input_name = 'input_' . $this->field->id;

		if ( empty( $_gf_uploaded_files ) ) {
			$_gf_uploaded_files = [];
		}
		if ( ! $this->field->multipleFiles && ! $this->is_draft ) {
			$this->input_value[0]['error'] = $this->input_value[0]['error'] ?? 0;
			$_FILES[ $input_name ]         = $this->input_value[0];
			return $_gf_uploaded_files[ $input_name ] ?? '';
		}

		$files = [];
		$urls  = [];

		$target = GFUtils::get_gravity_forms_upload_dir( $this->form['id'] );
		foreach ( $this->input_value as $single_value ) {
			if ( empty( $single_value['error'] ) ) {
				$single_value['error'] = 0;
			}

			$uploaded_file = GFUtils::handle_file_upload( $single_value, $target );

			// Set values needed for validation.
			if ( ! $this->field->multipleFiles ) {
				$_gf_uploaded_files[ $input_name ] = $uploaded_file['url'];

				GFFormsModel::$uploaded_files[ $this->field->formId ][ $input_name ] = $_gf_uploaded_files[ $input_name ];

				return $_gf_uploaded_files[ $input_name ];
			}

			gf_do_action( [ 'gform_post_multifile_upload', $this->form['id'] ], $this->form, $this->field, $single_value['name'], $single_value['tmp_name'], $uploaded_file['file'] );

			$files[] = [
				'temp_filename'     => $single_value['tmp_name'],
				'uploaded_filename' => $single_value['name'],
			];
			$urls[]  = $uploaded_file['url'];
		}

		$_gf_uploaded_files[ $input_name ] = wp_json_encode( array_values( $urls ) );

		if ( ! $_gf_uploaded_files[ $input_name ] ) {
			throw new UserError( __( 'Mutation failed. File paths couldnt be encoded as JSON.', 'wp-graphql-gravity-forms' ) );
		}

		GFFormsModel::$uploaded_files[ $this->field->formId ][ $input_name ] = $files;

		return $_gf_uploaded_files[ $input_name ];
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
}
