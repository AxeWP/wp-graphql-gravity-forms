<?php
/**
 * Abstract class for Mutations
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Mutations;

use GF_Field;
use GFCommon;
use GFSignature;
use GFFormsModel;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - DraftEntryUpdator
 */
abstract class AbstractMutation implements Hookable, Mutation {

	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name;

	/**
	 * Gravity Forms field validation errors.
	 *
	 * @var array
	 */
	protected $errors;

	/**
	 * Array of uploaded files. mimics $_FILES
	 *
	 * @var array
	 */
	protected $files = [];

	/**
	 * The form object.
	 *
	 * @var array
	 */
	protected $form;

	/**
	 * {@inheritDoc}.
	 */
	public function register_hooks() : void {
		add_action( get_graphql_register_action(), [ $this, 'register_mutation' ] );
	}

	/**
	 * Registers mutation.
	 */
	public function register_mutation() : void {
		register_graphql_mutation(
			static::$name,
			[
				'inputFields'         => $this->get_input_fields(),
				'outputFields'        => $this->get_output_fields(),
				'mutateAndGetPayload' => $this->mutate_and_get_payload(),
			]
		);
	}

	/**
	 * Checks that necessary WPGraphQL are set.
	 *
	 * @param mixed $input .
	 * @throws UserError .
	 */
	protected function check_required_inputs( $input ) : void {
		if ( empty( $input ) || ! is_array( $input ) ) {
			throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Saves field values have a flat [ $id => $value ] structure.
	 *
	 * @param GF_Field $field .
	 * @param mixed    $value .
	 * @return array
	 */
	protected function flatten_field_values( GF_Field $field, $value ) : array {
		$array = [];

		// For an array of sub-values, add each to the partial entry individually.
		if ( is_array( $value ) && ! in_array( $field->type, [ 'email', 'list', 'multiselect', 'post_category', 'post_custom', 'post_tags' ], true ) ) {
			foreach ( $value as $key => $single_value ) {
				$array[ $key ] = $single_value;
			}
			return $array;
		}
		if ( 'email' === $field->type ) {
			$array[ $field->id ]              = $value[0];
			$array[ $field->inputs[1]['id'] ] = $value[1];

			return $array;
		}

		// Else, add the single value to the partial entry.
		$array[ $field->id ] = $value;

		return $array;
	}

	/**
	 * Renames $field_value keys to input_{id}_{sub_id}, so Gravity Forms can read them.
	 *
	 * @param array $field_values .
	 * @return array
	 */
	protected function rename_keys_for_field_values( array $field_values ) : array {
		$formatted = [];

		foreach ( $field_values as $key => $value ) {
			$formatted[ 'input_' . str_replace( '.', '_', $key ) ] = $value;
		}
		return $formatted;
	}

	/**
	 * Generates array of field errors from the submission.
	 *
	 * @param array $messages The Gravity Forms submission validation messages.
	 * @return array
	 */
	protected function get_submission_errors( array $messages ) : array {
		return array_map(
			function( $id, $message ) {
				return [
					'id'      => $id,
					'message' => $message,
				];
			},
			array_keys( $messages ),
			$messages
		);
	}

	/**
	 * Disables validation for unsupported fields when submitting a form.
	 * Applied using the 'gform_field_validation' filter.
	 * Currently unsupported fields: captcha
	 *
	 * @param array    $result .
	 * @param mixed    $value .
	 * @param array    $form .
	 * @param GF_Field $field .
	 * @return array
	 */
	public function disable_validation_for_unsupported_fields( array $result, $value, array $form, GF_Field $field ) : array {
		if ( in_array( $field->type, [ 'captcha' ], true ) ) {
			$result = [
				'is_valid' => true,
				'message'  => __( 'This field type is not (yet) supported.', 'wp-graphql-gravity-forms' ),
			];
		}
		return $result;
	}

	/**
	 * Validates the field value type and then prepares the field value.
	 *
	 * @param array    $values input values.
	 * @param GF_Field $field .
	 * @param mixed    $prev_value Optional.
	 * @return mixed
	 */
	public function prepare_single_field_value( array $values, GF_Field $field, $prev_value = null ) {
		$this->validate_field_value_type( $field, $values );

		$value = $values['addressValues'] ?? $values['chainedSelectValues'] ?? $values['checkboxValues'] ?? $values['emailValues'] ?? $values['fileUploadValues'] ?? $values['listValues'] ?? $values['nameValues'] ?? $values['postImageValues'] ?? $values['values'] ?? $values['value'] ?? null;

		$value = $this->prepare_field_value_by_type( $value, $field, $prev_value );

		/**
		 * Filter to prepare the fieldValue for submissions to Gravity Forms.
		 *
		 * @param mixed $value the formatted value to be added to the GF submission object.
		 * @param array $input_values the unformatted input values.
		 * @param GF_Field $field .
		 * @param mixed $prev_value The previous submission value, if exists.
		 */
		$value = apply_filters( 'wp_graphql_gf_prepare_field_value', $value, $values, $field, $prev_value );

		return $value;
	}

	/**
	 * Adds the value to the field values array for processing by Gravity Forms.
	 *
	 * @param array    $values the existing field values array.
	 * @param GF_Field $field .
	 * @param mixed    $value_to_add .
	 * @return array
	 */
	public function add_value_to_array( array $values, GF_Field $field, $value_to_add ) : array {
		// Normal email fields are stored under their field id, but confirmation values are stored in a subfield.
		if ( 'email' === $field->type ) {
			$values[ $field->id ] = $value_to_add[0];
			if ( isset( $value_to_add[1] ) ) {
				$values[ $field->inputs[1]['id'] ] = $value_to_add[1];
			}
			return $values;
		}

		// Some fields are stored using their own array structure of subfields, so we're just adding it to to the values array as is.
		if ( in_array( $field->type, [ 'address', 'chainedselect', 'checkbox', 'consent', 'name', 'post_image' ], true ) ) {
			return $values + $value_to_add;
		}

		// Usually, fields are just stored by their id.
		$values[ $field->id ] = $value_to_add;
		return $values;
	}

	/**
	 * Validates the Gravity Forms field value.
	 *
	 * @param GF_Field $field .
	 * @param mixed    $value .
	 * @param mixed    $deprecated As of 0.6.2.
	 *
	 * @return mixed
	 */
	protected function validate_field_value( $field, $value, $deprecated = null ) {
		if ( ! empty( $deprecated ) ) {
			_doing_it_wrong( __FUNCTION__, 'This function no longer takes $form as its first argument.', '0.6.2' );

			// Reassign variables to match expected syntax.
			$field = $value;
			$value = $deprecated;
		}

		$field->validate( $value, $this->form );
		if ( $field->failed_validation ) {
			$this->errors[] = [
				'id'      => $field->id,
				'message' => $field->validation_message,
			];
		}
	}

	/**
	 * Checks that the proper GraphQL input type is used to submit the field values when submitting multiple fields at once.
	 * Used by SubmitForm and UpdateEntry classes.
	 *
	 * @param GF_Field $field .
	 * @param array    $values the `fieldValues` input array.
	 *
	 * @throws UserError .
	 */
	protected function validate_field_value_type( GF_Field $field, array $values ) : void {
		// Stores the name of the necessary field value type if it is missing from the mutation.
		$value_type_name = false;

		switch ( $field->type ) {
			case 'address':
				if ( ! isset( $values['addressValues'] ) ) {
					$value_type_name = 'addressValues';
				}
				break;
			case 'chainedselect':
				if ( ! isset( $values['chainedSelectValues'] ) ) {
					$value_type_name = 'chainedSelectValues';
				}
				break;
			case 'checkbox':
				if ( ! isset( $values['checkboxValues'] ) ) {
					$value_type_name = 'checkboxValues';
				}
				break;
			case 'email':
				if ( ! isset( $values['emailValues'] ) ) {
					$value_type_name = 'emailValues';
				}
				break;
			case 'fileupload':
				if ( ! isset( $values['fileUploadValues'] ) ) {
					$value_type_name = 'fileUploadValues';
				}
				break;
			case 'list':
				if ( ! isset( $values['listValues'] ) ) {
					$value_type_name = 'listValues';
				}
				break;
			case 'name':
				if ( ! isset( $values['nameValues'] ) ) {
					$value_type_name = 'nameValues';
				}
				break;
			case 'multiselect':
			case 'post_category':
			case 'post_custom':
			case 'post_tags':
				if ( ! isset( $values['values'] ) ) {
					$value_type_name = 'values';
				}
				break;
			case 'post_image':
				if ( ! isset( $values['postImageValues'] ) ) {
					$value_type_name = 'postImageValues';
				}
				break;
			default:
				if ( ! isset( $values['value'] ) ) {
					$value_type_name = 'value';
				}
				break;
		}

		/**
		 * Filter to set a custom valid fieldValue input type.
		 *
		 * @param string|false   $value_type_name The name of the missing input type. False if valid key exists.
		 * @param GF_Field $field The gravity forms field.
		 * @param array    $values the FieldValues input array.
		 */
		$value_type_name = apply_filters( 'wp_graphql_gf_field_value_type', $value_type_name, $field, $values );

		if ( false !== $value_type_name ) {
			// translators: Gravity Forms field id.
			throw new UserError( sprintf( __( 'Mutation not processed. Field %1$s requires the use of `%2$s`.', 'wp-graphql-gravity-forms' ), $field->id, $value_type_name ) );
		}
	}

	/**
	 * Formats and sanitizes the AddressField value.
	 *
	 * @param array    $value .
	 * @param GF_Field $field .
	 * @return array
	 */
	protected function prepare_address_field_value( array $value, GF_Field $field ) : array {
			return [
				$field['inputs'][0]['id'] => array_key_exists( 'street', $value ) ? sanitize_text_field( $value['street'] ) : null,
				$field['inputs'][1]['id'] => array_key_exists( 'lineTwo', $value ) ? sanitize_text_field( $value['lineTwo'] ) : null,
				$field['inputs'][2]['id'] => array_key_exists( 'city', $value ) ? sanitize_text_field( $value['city'] ) : null,
				$field['inputs'][3]['id'] => array_key_exists( 'state', $value ) ? sanitize_text_field( $value['state'] ) : null,
				$field['inputs'][4]['id'] => array_key_exists( 'zip', $value ) ? sanitize_text_field( $value['zip'] ) : null,
				$field['inputs'][5]['id'] => array_key_exists( 'country', $value ) ? sanitize_text_field( $value['country'] ) : null,
			];
	}

	/**
	 * Formats and sanitizes complex field values that are comprised of several input fields.
	 * Used by ChainedSelect and Checkbox fields.
	 *
	 * @param array    $value .
	 * @param GF_Field $field .
	 * @return array
	 */
	protected function prepare_complex_field_value( array $value, GF_Field $field ) : array {
		$values_to_save = array_reduce(
			$field->inputs,
			function( array $values_to_save, array $input ) : array {
				$values_to_save[ $input['id'] ] = ''; // Initialize all inputs to an empty string.
				return $values_to_save;
			},
			[]
		);

		foreach ( $value as $single_value ) {
			$input_id    = sanitize_text_field( $single_value['inputId'] );
			$input_value = sanitize_text_field( $single_value['value'] );

			// Make sure the input ID passed in exists.
			if ( ! isset( $values_to_save[ $input_id ] ) ) {
				continue;
			}

			// Overwrite initial empty string with the value passed in.
			$values_to_save[ $input_id ] = $input_value;
		}

		return $values_to_save;
	}

	/**
	 * Formats and sanitizes the ConsentField value.
	 *
	 * @param bool     $value .
	 * @param GF_Field $field .
	 * @return array
	 */
	protected function prepare_consent_field_value( bool $value, GF_Field $field ) : array {
		return [
			$field->inputs[0]['id'] => (bool) $value,
			$field->inputs[1]['id'] => isset( $field->checkboxLabel ) ? sanitize_text_field( $field->checkboxLabel ) : null,
			$field->inputs[2]['id'] => isset( $field->descriptiom ) ? sanitize_text_field( $field->description ) : null,
		];
	}

	/**
	 * Formats and sanitizes the email field value.
	 *
	 * @param array    $value .
	 * @param GF_Field $field .
	 * @return array
	 */
	protected function prepare_email_field_value( array $value, GF_Field $field ) : array {
		$values_to_save = [];

		$values_to_save[] = isset( $value['value'] ) ? sanitize_text_field( $value['value'] ) : null;

		if ( $field->emailConfirmEnabled ) {
			$values_to_save[] = isset( $value['confirmationValue'] ) ? sanitize_text_field( $value['confirmationValue'] ) : null;
		}

		return $values_to_save;
	}

	/**
	 * Save file upload value
	 *
	 * @param mixed    $value The file upload object.
	 * @param GF_Field $field .
	 * @param string   $prev_value the previous file upload urls.
	 * @return string|null
	 * @throws UserError If WPGrahQL Upload isn't activated.
	 */
	protected function prepare_file_upload_field_value( $value, GF_Field $field, $prev_value = null ) {
		if ( ! class_exists( 'WPGraphQL\Upload\Type\Upload' ) ) {
			throw new UserError( __( 'To upload files you must enable the WPGraphQL Upload plugin!.', 'wp-graphql-gravity-forms' ) );
		}

		// Let people know this is a workaround until there's native WPGraphQL support.
		graphql_debug( __( 'File upload support is experimental, and current relies on WPGraphQL Upload.', 'wp-graphql-gravity-forms' ) );

		$target = GFUtils::get_gravity_forms_upload_dir( $field->formId );

		// Gravity Forms uses $_gf_uploaded_files to store and validate multipleFile uploads.
		global $_gf_uploaded_files;
		if ( empty( $_gf_uploaded_files ) ) {
			$_gf_uploaded_files = [];
		}

		$input_name = 'input_' . $field->id;

		if ( isset( $_gf_uploaded_files[ $input_name ] ) ) {
			return $_gf_uploaded_files[ $input_name ];
		}

		// Delete previous file, if exists.
		$this->delete_previous_files( $prev_value );

		$files = [];
		$urls  = [];
		foreach ( $value as $single_value ) {
			// Initialize files, since GF_Field_FileUpload expects it.

			if ( ! array_key_exists( 'error', $single_value ) || empty( $single_value['error'] ) ) {
				$single_value['error'] = 0;
			}

			$is_uploaded = GFUtils::handle_file_upload( $single_value, $target );

			if ( ! $is_uploaded ) {
				continue;
			}

			// Set values needed for validation.
			if ( ! $field->multipleFiles ) {
				$_FILES[ $input_name ]             = $single_value;
				$_gf_uploaded_files[ $input_name ] = $is_uploaded['url'];
				GFFormsModel::$uploaded_files[ $field->formId ][ $input_name ] = $_gf_uploaded_files[ $input_name ];

				return $_gf_uploaded_files[ $input_name ];
			}

			$files[] = [
				'temp_filename'     => $single_value['tmp_name'],
				'uploaded_filename' => $single_value['name'],
			];
			array_push( $urls, $is_uploaded['url'] );
		}

		$_gf_uploaded_files[ $input_name ] = wp_json_encode( array_values( $urls ) );

		GFFormsModel::$uploaded_files[ $field->formId ][ $input_name ] = $files;

		return $_gf_uploaded_files[ $input_name ] ?: null;
	}

	/**
	 * Saves post_image field value.
	 *
	 * @param array    $value .
	 * @param GF_Field $field .
	 * @param string   $prev_value the file upload encoded url.
	 */
	protected function prepare_post_image_field_value( array $value, GF_Field $field, $prev_value = null ) : array {
		$prev_value = array_pad( explode( '|:|', (string) $prev_value ), 4, false );

		$url         = array_key_exists( 'image', $value ) ? $this->prepare_file_upload_field_value( [ $value['image'] ], $field, $prev_value[0] ?? null ) : $prev_value[0] ?? null;
		$title       = array_key_exists( 'title', $value ) ? wp_strip_all_tags( $value['title'] ) : $prev_value[1] ?? null;
		$caption     = array_key_exists( 'caption', $value ) ? wp_strip_all_tags( $value['caption'] ) : $prev_value[1] ?? null;
		$description = array_key_exists( 'description', $value ) ? wp_strip_all_tags( $value['description'] ) : $prev_value[3] ?? null;
		$alt         = array_key_exists( 'altText', $value ) ? wp_strip_all_tags( $value['altText'] ) : $prev_value[4] ?? null;

		$_POST[ 'input_' . $field->id . '_0' ] = $url;
		$_POST[ 'input_' . $field->id . '_1' ] = $title;
		$_POST[ 'input_' . $field->id . '_4' ] = $caption;
		$_POST[ 'input_' . $field->id . '_7' ] = $description;
		$_POST[ 'input_' . $field->id . '_2' ] = $alt;

		return [
			$field->id . '_0' => $url,
			$field->id . '_1' => $title,
			$field->id . '_2' => $alt,
			$field->id . '_4' => $caption,
			$field->id . '_7' => $description,
		];
	}

	/**
	 * Formats and sanitizes ListField values.
	 *
	 * @param array $value .
	 * @return array
	 */
	protected function prepare_list_field_value( array $value ) : array {
		$values_to_save = [];
		foreach ( $value as $row ) {
			foreach ( $row as $row_values ) {
				foreach ( $row_values as $single_value ) {
					$values_to_save[] = sanitize_text_field( $single_value );
				}
			}
		}
		return $values_to_save;
	}

	/**
	 * Formats and sanitizes the NameField value.
	 *
	 * @param array    $value .
	 * @param GF_Field $field .
	 * @return array
	 */
	protected function prepare_name_field_value( array $value, GF_Field $field ) : array {
		return [
			$field['inputs'][0]['id'] => array_key_exists( 'prefix', $value ) ? sanitize_text_field( $value['prefix'] ) : null,
			$field['inputs'][1]['id'] => array_key_exists( 'first', $value ) ? sanitize_text_field( $value['first'] ) : null,
			$field['inputs'][2]['id'] => array_key_exists( 'middle', $value ) ? sanitize_text_field( $value['middle'] ) : null,
			$field['inputs'][3]['id'] => array_key_exists( 'last', $value ) ? sanitize_text_field( $value['last'] ) : null,
			$field['inputs'][4]['id'] => array_key_exists( 'suffix', $value ) ? sanitize_text_field( $value['suffix'] ) : null,
		];
	}

	/**
	 * Sanitizes the PostContentField value.
	 *
	 * @param string $value .
	 * @return string
	 */
	protected function prepare_post_content_field_value( string $value ) : string {
		return wp_kses_post( $value );
	}

	/**
	 * Formats and sanitizes field string array field values.
	 * Used by MultiSelect, PostCategory, PostCustom, and PostTags fields.
	 *
	 * @param array $value .
	 * @return array
	 */
	protected function prepare_string_array_value( array $value ) : array {
		return array_map( 'sanitize_text_field', $value );
	}

	/**
	 * Sanitizes string field values.
	 * Used by Date, Hidden, Number, Phone, PostExcerpt, PostTitle, Radio, Select, TextArea, Text, and Time fields.
	 *
	 * @param string $value .
	 * @return string
	 */
	protected function prepare_string_value( string $value ) : string {
		return sanitize_text_field( $value );
	}

	/**
	 * Sanitizes the WebsiteField value.
	 *
	 * @param string $value .
	 * @return string
	 */
	protected function prepare_website_field_value( string $value ) : string {
		return esc_url_raw( $value );
	}

	/**
	 * Saves Signature field value.
	 *
	 * @param string $value Base-64 encoded png signature image value.
	 * @param string $prev_filename The old signature filename, if it exists.
	 *
	 * @return string The filename of the saved signature image file.
	 */
	protected function prepare_signature_field_value( string $value, string $prev_filename = null ) : string {
		$this->ensure_signature_plugin_is_active();
		$this->ensure_signatures_folder_exists();
		$this->delete_previous_signature_image( $prev_filename );

		return $this->save_signature( $value );
	}

	/**
	 * Checks if the Gravity Forms Signature Add-On plugin is active.
	 *
	 * @throws UserError .
	 */
	private function ensure_signature_plugin_is_active() : void {
		if ( ! class_exists( 'GFSignature' ) ) {
			throw new UserError( __( 'The Gravity Forms Signature Add-On plugin must be active for signature field values to be saved.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Ensures the folder for storing signatures exists.
	 *
	 * @throws UserError .
	 */
	private function ensure_signatures_folder_exists() : void {
		$folder = GFSignature::get_signatures_folder();
		$exists = wp_mkdir_p( $folder );

		if ( ! $exists ) {
			throw new UserError( __( 'The Gravity Forms Signatures directory could not be created.', 'wp-graphql-gravity-forms' ) );
		}

		// Add index.html to prevent directory browsing.
		GFCommon::recursive_add_index_file( $folder );
	}

	/**
	 * Deletes the previous signature image
	 *
	 * @param string $prev_filename The file name of the image to delete.
	 */
	private function delete_previous_signature_image( string $prev_filename = null ) : void {
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
	 * Copy of GFFormsModel::delete_physical_file.
	 *
	 * @param string $prev_url .
	 */
	private function delete_previous_files( $prev_url = null ) : void {
		if ( ! $prev_url ) {
			return;
		}

		// Create array of urls for deletion loop.
		$files_to_delete = json_decode( $prev_url, true );
		if ( 0 !== json_last_error() ) {
			$files_to_delete = [ $prev_url ];
		}

		foreach ( $files_to_delete as $file ) {
			$ary = explode( '|:|', $file );
			$url = $ary[0];

			if ( empty( $url ) ) {
				break;
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

		$folder   = GFSignature::get_signatures_folder();
		$filename = uniqid( '', true ) . '.png';
		$path     = $folder . $filename;
		// @TODO: switch to WP Filesystem.
		$number_of_bytes = file_put_contents( $path, $signature_decoded ); //phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

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

	/**
	 * Prepares the field value based on the field type.
	 *
	 * @param mixed    $value .
	 * @param GF_Field $field .
	 * @param mixed    $prev_value .
	 * @return mixed
	 */
	public function prepare_field_value_by_type( $value, GF_Field $field, $prev_value = null ) {
		$prepared_value = null;

		switch ( $field->type ) {
			case 'address':
				$prepared_value = $this->prepare_address_field_value( $value, $field );
				break;
			case 'chainedselect':
			case 'checkbox':
				$prepared_value = $this->prepare_complex_field_value( $value, $field );
				break;
			case 'consent':
				$prepared_value = $this->prepare_consent_field_value( $value, $field );
				break;
			case 'email':
				$prepared_value = $this->prepare_email_field_value( $value, $field );
				break;
			case 'fileupload':
				$prepared_value = $this->prepare_file_upload_field_value( $value, $field, $prev_value );
				break;
			case 'list':
				$prepared_value = $this->prepare_list_field_value( $value );
				break;
			case 'multiselect':
			case 'post_category':
			case 'post_custom':
			case 'post_tags':
				$prepared_value = $this->prepare_string_array_value( $value );
				break;
			case 'name':
				$prepared_value = $this->prepare_name_field_value( $value, $field );
				break;
			case 'post_content':
				$prepared_value = $this->prepare_post_content_field_value( $value );
				break;
			case 'post_image':
				$prepared_value = $this->prepare_post_image_field_value( $value, $field, $prev_value );
				break;
			case 'signature':
				$prepared_value = $this->prepare_signature_field_value( $value, $prev_value );

				// Save values to $_POST for GF validation.
				if ( ! empty( $prepared_value ) ) {
					$_POST[ 'input_' . $field->formId . '_' . $field->id . '_signature_filname' ] = $prepared_value;
					$_POST[ 'input_' . $field->formId . '_' . $field->id . '_valid' ]             = true;
				}

				return $prepared_value;
			case 'website':
				$prepared_value = $this->prepare_website_field_value( $value );
				break;
			case 'date':
			case 'hidden':
			case 'number':
			case 'phone':
			case 'post_excerpt':
			case 'post_title':
			case 'radio':
			case 'select':
			case 'textarea':
			case 'text':
			case 'time':
				$prepared_value = $this->prepare_string_value( $value );
				break;
		}

		return $prepared_value;
	}

	/**
	 * Initializes the $_FILES array with the fileupload `input_{id}`.
	 * This prevents any notices about missing array keys.
	 */
	public function initialize_files() : void {
		foreach ( $this->form['fields'] as $field ) {
			if ( 'post_image' === $field->type || ( 'fileupload' === $field->type && ! $field->multipleFiles ) ) {
				$_FILES[ 'input_' . $field->id ] = [
					'name'     => null,
					'type'     => null,
					'size'     => null,
					'tmp_name' => null,
					'error'    => null,
				];
			}
		}
	}
}
