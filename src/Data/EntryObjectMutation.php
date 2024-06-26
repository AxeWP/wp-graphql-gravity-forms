<?php
/**
 * Helper functions for mutations handling form submissions
 *
 * @package WPGraphQL\GF\Data
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Data;

use Exception;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Data\FieldValueInput;
use WPGraphQL\GF\Data\FieldValueInput\AbstractFieldValueInput;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - EntryObjectMutation
 */
class EntryObjectMutation {
	/**
	 * Returns the FieldValueInput object relative to the field type.
	 *
	 * @param array<string,mixed>     $args     The GraphQL mutation input args for the field.
	 * @param array<string,mixed>     $form     The GF form object.
	 * @param bool                    $is_draft If the mutation is for a draft entry.
	 * @param array<int|string,mixed> $entry    The GF entry object. Used when updating.
	 *
	 * @throws \Exception .
	 */
	public static function get_field_value_input( array $args, array $form, bool $is_draft, ?array $entry = null ): FieldValueInput\AbstractFieldValueInput {
		$field = GFUtils::get_field_by_id( $form, $args['id'] );

		$input_type = $field->get_input_type();

		switch ( $input_type ) {
			case 'address':
				$field_value_input = FieldValueInput\AddressValuesInput::class;
				break;
			case 'captcha':
				$field_value_input = FieldValueInput\CaptchaValueInput::class;
				break;
			case 'checkbox':
				$field_value_input = FieldValueInput\CheckboxValuesInput::class;
				break;
			case 'consent':
				$field_value_input = FieldValueInput\ConsentValueInput::class;
				break;
			case 'email':
				$field_value_input = FieldValueInput\EmailValuesInput::class;
				break;
			case 'fileupload':
				$field_value_input = FieldValueInput\FileUploadValuesInput::class;
				break;
			case 'list':
				$field_value_input = FieldValueInput\ListValuesInput::class;
				break;
			case 'multiselect':
				$field_value_input = FieldValueInput\ValuesInput::class;
				break;
			case 'name':
				$field_value_input = FieldValueInput\NameValuesInput::class;
				break;
			case 'post_image':
				$field_value_input = FieldValueInput\ImageValuesInput::class;
				break;
			case 'hiddenproduct':
			case 'singleproduct':
			case 'calculation':
				$field_value_input = FieldValueInput\ProductValueInput::class;
				break;
			case 'radio':
				$field_value_input = FieldValueInput\RadioValueInput::class;
				break;
			case 'date':
			case 'hidden':
			case 'number':
			case 'phone':
			case 'post_content':
			case 'post_excerpt':
			case 'post_title':
			case 'price':
			case 'select':
			case 'text':
			case 'textarea':
			case 'time':
			case 'website':
			default:
				$field_value_input = FieldValueInput\ValueInput::class;
		}

		/**
		 * Filters the FieldValueInput instance used to process form field submissions.
		 *
		 * Useful for adding mutation support for custom fields.
		 *
		 * @param string                   $field_value_input_class  The FieldValueInput class to use. The referenced class must extend AbstractFieldValueInput.
		 * @param array                    $args The GraphQL input args for the form field.
		 * @param \GF_Field                $field The current Gravity Forms field object.
		 * @param array<string,mixed>      $form The current Gravity Forms form object.
		 * @param ?array<int|string,mixed> $entry The current Gravity Forms entry object. Only available when using update (`gfUpdateEntry`, `gfUpdateDraftEntry`) mutations.
		 * @param bool                     $is_draft_mutation Whether the mutation is handling a Draft Entry (`gfUpdateDraftEntry`, or `gfSubmitForm` when `saveAsDraft` is `true`).
		 */
		$field_value_input = apply_filters( 'graphql_gf_field_value_input_class', $field_value_input, $args, $field, $form, $entry, $is_draft );

		if ( ! is_a( $field_value_input, AbstractFieldValueInput::class, true ) ) {
			throw new Exception( esc_html__( 'Invalid FieldValueInput class. Classes must extend AbstractFieldValueInput.', 'wp-graphql-gravity-forms' ) );
		}

		return new $field_value_input( $args, $form, $is_draft, $field, $entry );
	}

	/**
	 * Generates array of field errors from the submission.
	 *
	 * @param array<int|string,string> $messages The Gravity Forms submission validation messages.
	 * @param int                      $form_id  The ID of the form.
	 *
	 * @return array{message:string,id:int|string}[]
	 */
	public static function get_submission_errors( array $messages, int $form_id ): array {
		return array_map(
			static function ( $id, $message ) use ( $form_id ): array {
				return [
					'id'      => $id,
					'message' => $message,
					'formId'  => $form_id,
				];
			},
			array_keys( $messages ),
			$messages
		);
	}

	/**
	 * Gets the submission confirmation information in an array formated for WPGraphQL.
	 *
	 * @param array<string,mixed> $payload the submission response.
	 *
	 * @return ?array{type:string,message:?string,url:?string}
	 */
	public static function get_submission_confirmation( array $payload ): ?array {
		if ( empty( $payload['confirmation_type'] ) ) {
			return null;
		}

		return [
			'type'    => $payload['confirmation_type'],
			'message' => $payload['confirmation_message'] ?? null,
			'url'     => $payload['confirmation_redirect'] ?? null,
		];
	}

	/**
	 * Renames $field_value keys to input_{id}_{sub_id}, so Gravity Forms can read them.
	 *
	 * @param array<int|string,mixed> $field_values .
	 *
	 * @return array<string,mixed> $formatted .
	 * */
	public static function rename_field_names_for_submission( array $field_values ): array {
		$formatted = [];

		foreach ( $field_values as $key => $value ) {
			$formatted[ 'input_' . str_replace( '.', '_', (string) $key ) ] = $value;
		}

		return $formatted;
	}

	/**
	 * Initializes the globals needed for file uploads to work.
	 * This prevents any notices about missing array keys.
	 *
	 * @param \GF_Field[]           $form_fields .
	 * @param array<string,mixed>[] $input_field_values .
	 * @param bool                  $save_as_draft .
	 *
	 * @return array<string,array<string,mixed>[]>
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function initialize_files( array $form_fields, array $input_field_values, bool $save_as_draft ): array {
		$files = [];

		// Loop through all the fields to see if there are any upload types.
		foreach ( $form_fields as $field ) {
			// Bail early if not a file field.
			if ( 'post_image' !== $field->get_input_type() && 'fileupload' !== $field->get_input_type() ) {
				continue;
			}

			$input_name = 'input_' . $field->id;

			// Single files need to be in $_FILES.
			if ( ! $field->multipleFiles && ! isset( $_FILES[ $input_name ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$_FILES[ $input_name ] = [
					'name'     => null,
					'type'     => null,
					'size'     => null,
					'tmp_name' => null,
					'error'    => null,
				];

				continue;
			}

			// Even though draft entries don't upload anything, GF still needs the $_FILES array.
			if ( $save_as_draft ) {
				continue;
			}

			// Build multiupload filedata so the parent can save them to $_POST[`gform_uploaded_files`].
			$file_payloads = [];
			$target_dir    = \GFFormsModel::get_upload_path( $field->formId ) . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;

			if ( ! is_dir( $target_dir ) ) {
				if ( ! wp_mkdir_p( $target_dir ) ) {
					throw new UserError( esc_html__( 'Unable to create directory for file uploads.', 'wp-graphql-gravity-forms' ) );
				}
			}
			foreach ( $input_field_values as $value ) {
				if ( $value['id'] !== $field->id || empty( $value['fileUploadValues'] ) ) {
					continue;
				}

				foreach ( $value['fileUploadValues'] as $file ) {
					// Uploads the files to the GF temp directory.
					$temp_filename = 'input_' . $field->id . '_' . \GFCommon::random_str( 16 ) . '_' . $file['name'];
					$target_file   = $target_dir . wp_basename( $temp_filename );
					$is_success    = copy( $file['tmp_name'], $target_file );
					if ( $is_success && file_exists( $target_file ) ) {
						\GFFormsModel::set_permissions( $target_file );
					}

					$file_payloads[] = [
						'temp_filename'     => $temp_filename,
						'uploaded_filename' => $file['name'],
					];
				}
			}

			if ( ! empty( $file_payloads ) ) {
				$files[ $input_name ] = $file_payloads;
			}
		}

		return $files;
	}
}
