<?php
/**
 * Manipulates input data for FileUpload field values.
 *
 * @package WPGraphQL\GF\Data\FieldValueInput
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data\FieldValueInput;

use GraphQL\Error\UserError;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FileUploadValuesInput
 */
class FileUploadValuesInput extends AbstractFieldValueInput {
	/**
	 * {@inheritDoc}
	 *
	 * @var array{error:int,name:string,size:int,tmp_name:string,type:string}[]
	 */
	protected $args;

	/**
	 * {@inheritDoc}
	 */
	protected function get_field_name(): string {
		return 'fileUploadValues';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return string|mixed
	 *
	 * @throws \GraphQL\Error\UserError
	 */
	protected function prepare_value() {
		if ( $this->is_draft ) {
			return '';
		}

		if ( ! Utils::is_graphql_upload_enabled() ) {
			throw new UserError( esc_html__( 'To upload files you must enable the WPGraphQL Upload plugin!.', 'wp-graphql-gravity-forms' ) );
		}

		// Let people know this is a workaround until there's native WPGraphQL support.
		graphql_debug( __( 'File upload support is experimental and current relies on WPGraphQL Upload.', 'wp-graphql-gravity-forms' ) );

		$input_name = 'input_' . $this->field->id;
		// Manually move single files.
		if ( ! $this->field->multipleFiles ) {
			$this->args[0]['error'] = $this->args[0]['error'] ?? UPLOAD_ERR_OK;
			$_FILES[ $input_name ]  = $this->args[0];
		}

		// MultiUploads are handled by $_POST['gform_uploaded_files'].
		return '';
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_value_to_submission( array &$field_values ): void {
		if ( empty( $this->entry ) || $this->is_draft || ! empty( $this->field->multipleFields ) ) {
			return;
		}

		$input_name = 'input_' . $this->field->id;
		/** @var string $value */
		$value = $this->value;

		$field_values[ $this->field->id ] = $this->field->get_value_save_entry( $value, $this->form, $input_name, $this->entry['id'] ?? null, $this->entry );
	}
}
