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
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;

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
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_mutation' ] );
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
		if ( is_array( $value ) && ! in_array( $field->type, [ 'list', 'multiselect', 'post_category', 'post_custom', 'post_tags' ], true ) ) {
			foreach ( $value as $key => $single_value ) {
				$array[ $key ] = $single_value;
			}
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
	 * Currently unsupported fields: captcha, fileupload, post_image
	 *
	 * @param array    $result .
	 * @param mixed    $value .
	 * @param array    $form .
	 * @param GF_Field $field .
	 * @return array
	 */
	public function disable_validation_for_unsupported_fields( array $result, $value, array $form, GF_Field $field ) : array {
		if ( in_array( $field->type, [ 'captcha', 'fileupload', 'post_image' ], true ) ) {
			$result = [
				'is_valid' => true,
				'message'  => __( 'This field type is not (yet) supported.', 'wp-graphql-gravity-forms' ),
			];
		}
		return $result;
	}

	/**
	 * Validates the Gravity Forms field value.
	 *
	 * @param array    $form .
	 * @param GF_Field $field .
	 * @param mixed    $value .
	 *
	 * @return mixed
	 */
	protected function validate_field_value( array $form, GF_Field $field, $value ) {
		$field->validate( $value, $form );
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
		switch ( $field->type ) {
			case 'address':
				if ( ! isset( $values['addressValues'] ) ) {
					// translators: Gravity Forms field id.
					throw new UserError( sprintf( __( 'Mutation not processed. Field %s requires the use of `addressValues`.', 'wp-graphql-gravity-forms' ), $field->id ) );
				}
				break;
			case 'chainedselect':
				if ( ! isset( $values['chainedSelectValues'] ) ) {
					// translators: Gravity Forms field id.
					throw new UserError( sprintf( __( 'Mutation not processed. Field %s requires the use of `chainedSelectValues`.', 'wp-graphql-gravity-forms' ), $field->id ) );
				}
				break;
			case 'checkbox':
				if ( ! isset( $values['checkboxValues'] ) ) {
					// translators: Gravity Forms field id.
					throw new UserError( sprintf( __( 'Mutation not processed. Field %s requires the use of `checkboxValues`.', 'wp-graphql-gravity-forms' ), $field->id ) );
				}
				break;
			case 'list':
				if ( ! isset( $values['listValues'] ) ) {
					// translators: Gravity Forms field id.
					throw new UserError( sprintf( __( 'Mutation not processed. Field %s requires the use of `listValues`.', 'wp-graphql-gravity-forms' ), $field->id ) );
				}
				break;
			case 'name':
				if ( ! isset( $values['nameValues'] ) ) {
					// translators: Gravity Forms field id.
					throw new UserError( sprintf( __( 'Mutation not processed. Field %s requires the use of `nameValues`.', 'wp-graphql-gravity-forms' ), $field->id ) );
				}
				break;
			case 'multiselect':
			case 'post_category':
			case 'post_custom':
			case 'post_tags':
				if ( ! isset( $values['values'] ) ) {
					// translators: Gravity Forms field id.
					throw new UserError( sprintf( __( 'Mutation not processed. Field %s requires the use of `values`.', 'wp-graphql-gravity-forms' ), $field->id ) );
				}
				break;
			default:
				if ( ! isset( $values['value'] ) ) {
					// translators: Gravity Forms field id.
					throw new UserError( sprintf( __( 'Mutation not processed. Field %s requires the use of `value`.', 'wp-graphql-gravity-forms' ), $field->id ) );
				}
				break;
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
	 * @return mixed
	 */
	public function prepare_field_value_by_type( $value, GF_Field $field ) {
		switch ( $field->type ) {
			case 'address':
				return $this->prepare_address_field_value( $value, $field );
			case 'chainedselect':
			case 'checkbox':
				return $this->prepare_complex_field_value( $value, $field );
			case 'consent':
				return $this->prepare_consent_field_value( $value, $field );
			case 'list':
				return $this->prepare_list_field_value( $value );
			case 'multiselect':
			case 'post_category':
			case 'post_custom':
			case 'post_tags':
				return $this->prepare_string_array_value( $value );
			case 'name':
				return $this->prepare_name_field_value( $value, $field );
			case 'post_content':
				return $this->prepare_post_content_field_value( $value );
			case 'signature':
				return $this->prepare_signature_field_value( $value );
			case 'website':
				return $this->prepare_website_field_value( $value );
			case 'date':
			case 'email':
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
			default:
				return $this->prepare_string_value( $value );
		}
	}
}
