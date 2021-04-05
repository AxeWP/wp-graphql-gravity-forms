<?php
/**
 * GF Utils
 *
 * Wrappers for common Gravity Forms functions.
 *
 * @package WPGraphQLGravityForms\Utils
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Utils;

use GF_Field;
use GFAPI;
use GFCommon;
use GFFormDisplay;
use GFFormsModel;
use GraphQL\Error\UserError;

/**
 * Class - GFUtils
 */
class GFUtils {
	/**
	 * Returns IP address.
	 * Uses GFFormsModel::get_ip()
	 *
	 * @param string $ip .
	 * @return string
	 */
	public static function get_ip( string $ip ) : string {
			return ! empty( $ip ) ? sanitize_text_field( $ip ) : GFFormsModel::get_ip();
	}

	/**
	 * Gets the Gravity Form form object for the given form ID.
	 * Uses GFAPI::get_form().
	 *
	 * @see https://docs.gravityforms.com/api-functions/#get-form
	 *
	 * @param integer $form_id .
	 * @param bool    $active_only Whether to only return the form if it is active.
	 * @return array
	 *
	 * @throws UserError .
	 */
	public static function get_form( int $form_id, bool $active_only = true ) : array {
		$form = GFAPI::get_form( $form_id );

		if ( ! $form ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( __( 'Unable to retrieve the form for the given ID %s', 'wp-graphql-gravity-forms' ), $form_id ),
			);
		}

		if ( $active_only && ( ! $form['is_active'] || $form['is_trash'] ) ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( __( 'The form for the given ID %s is inactive or trashed.', 'wp-graphql-gravity-forms' ), $form_id ),
			);
		}

		return $form;
	}

	/**
	 * Gets the last page of the form. Useful for form submissions.
	 *
	 * @param array $form .
	 */
	public static function get_last_form_page( array $form ) : int {
		require_once GFCommon::get_base_path() . '/form_display.php';

		return GFFormDisplay::get_max_page_number( $form );
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::get_form_unique_id() method.
	 *
	 * @param int $form_id Form ID.
	 *
	 * @return string Unique ID.
	 */
	public static function get_form_unique_id( int $form_id ) : string {
		if ( ! isset( GFFormsModel::$unique_ids[ $form_id ] ) ) {
			GFFormsModel::$unique_ids[ $form_id ] = uniqid();
		}

		return GFFormsModel::$unique_ids[ $form_id ];
	}

	/**
	 * Returns Gravity Forms Field object for given field id.
	 *
	 * @param array $form     The form.
	 * @param int   $field_id Field ID.
	 *
	 * @return GF_Field
	 *
	 * @throws UserError .
	 */
	public static function get_field_by_id( array $form, int $field_id ) : GF_Field {
		$matching_fields = array_values(
			array_filter(
				$form['fields'],
				function( GF_Field $field ) use ( $field_id ) : bool {
					return $field['id'] === $field_id;
				}
			)
		);

		if ( ! $matching_fields ) {
			throw new UserError(
				// translators: Gravity Forms form id and field id.
				sprintf( __( 'The form (ID %1$s) does not contain a field with the field ID %2$s.', 'wp-graphql-gravity-forms' ), $form['id'], $field_id )
			);
		}

		return $matching_fields[0];
	}


	/**
	 * Gets the Gravity Form entry object for the given form ID.
	 * Uses GFAPI::get_entry().
	 *
	 * @see https://docs.gravityforms.com/api-functions/#get-entry
	 *
	 * @param integer $entry_id .
	 * @return array
	 *
	 * @throws UserError .
	 */
	public static function get_entry( int $entry_id ) : array {
		$entry = GFAPI::get_entry( $entry_id );

		if ( is_wp_error( $entry ) ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( __( 'The entry for the given ID %s was not found. Error: ', 'wp-graphql-gravity-forms' ), $entry_id ) . $entry->get_error_message()
			);
		}

		return $entry;
	}


	/**
	 * Updates the existing Gravity Form entry.
	 * Uses GFAPI::update_entry().
	 *
	 * @see https://docs.gravityforms.com/api-functions/#update-entry
	 *
	 * @param array $entry_data .
	 * @param int   $entry_id .
	 * @return integer
	 *
	 * @throws UserError .
	 */
	public static function update_entry( array $entry_data, int $entry_id = null ) : int {
		$entry_id = $entry_id ?? $entry_data['id'];

		$is_entry_updated = GFAPI::update_entry( $entry_data, $entry_id );

		if ( is_wp_error( ( $is_entry_updated ) ) ) {
			throw new UserError(
				// translators: Gravity Forms entry id.
				sprintf( __( 'An error occured while trying to update the entry (ID: %s). Error: ', 'wp-graphql-gravity-forms' ), $entry_data['id'] ) . $is_entry_updated->get_error_message()
			);
		}

		return $entry_id;
	}


	/**
	 * Returns draft entry array from a given resume token.
	 * Uses GFFormsModel::get_draft_submission_values().
	 *
	 * @param string $resume_token .
	 * @return array
	 *
	 * @throws UserError .
	 */
	public static function get_draft_entry( string $resume_token ) : array {
		$draft_entry = GFFormsModel::get_draft_submission_values( $resume_token );

		if ( ! is_array( $draft_entry ) || empty( $draft_entry ) ) {
			throw new UserError(
				// translators: Gravity Forms form id and field id.
				sprintf( __( 'A draft entry with the resume token %s could not be found.', 'wp-graphql-gravity-forms' ), $resume_token )
			);
		}

		return $draft_entry;
	}



	/**
	 * Returns draft entry submittion data.
	 *
	 * @param string $resume_token Draft entry resume token.
	 *
	 * @return array
	 *
	 * @throws UserError .
	 */
	public static function get_draft_submission( string $resume_token ) : array {
		$draft_entry = self::get_draft_entry( $resume_token );

		$submission = json_decode( $draft_entry['submission'], true );

		if ( ! $submission ) {
			throw new UserError(
					// translators: Gravity Forms form id and field id.
				sprintf( __( 'The draft entry submission data for the resume token %s could not be read.', 'wp-graphql-gravity-forms' ), $resume_token )
			);
		}

		return $submission;
	}



	/**
	 * Get the draft resume URL.
	 *
	 * @param string     $source_url   Source URL.
	 * @param string     $resume_token Resume token.
	 * @param array|null $form         Form object.
	 *
	 * @return string Resume URL, or empty string if no source URL was provided.
	 */
	public static function get_resume_url( string $source_url, string $resume_token, $form = [] ) : string {
		if ( ! $source_url ) {
			return '';
		}

		/**
		 * Filters the 'Save and Continue' URL to be used with a partial entry submission.
		 *
		 * @param string $resume_url   The URL to be used to resume the partial entry.
		 * @param array  $form         The Form Object.
		 * @param string $resume_token The token that is used within the URL.
		 * @param string $unused       Unused parameter. Included for consistency with the native
		 *                             Gravity Forms gform_save_and_continue_resume_url hook.
		 */
		return esc_url(
			apply_filters(
				'gform_save_and_continue_resume_url',
				add_query_arg( [ 'gf_token' => $resume_token ], $source_url ),
				$form,
				$resume_token,
				''
			)
		);
	}

	/**
	 * Saves Gravity Forms draft entry.
	 * Uses GFFormsModel::save_draft_submission().
	 *
	 * @param array   $form .
	 * @param array   $entry .
	 * @param array   $field_values .
	 * @param integer $page_number .
	 * @param array   $files .
	 * @param string  $form_unique_id .
	 * @param string  $ip .
	 * @param string  $source_url .
	 * @param string  $resume_token .
	 * @return string
	 *
	 * @throws UserError .
	 */
	public static function save_draft_submission( array $form, array $entry, array $field_values = null, int $page_number = 1, array $files = [], string $form_unique_id = null, string $ip = '', string $source_url = '', string $resume_token = '' ) : string {
		if ( empty( $form ) || empty( $entry ) ) {
			throw new UserError( __( 'An error occured while trying to save the draft entry. Form or Entry not set.', 'wp-graphql-gravity-forms' ) );
		}

		$form_unique_id = $form_unique_id ?? self::get_form_unique_id( $form['id'] );

		$new_resume_token = GFFormsModel::save_draft_submission(
			$form,
			$entry,
			$field_values,
			$page_number,
			$files,
			$form_unique_id,
			$ip,
			$source_url,
			$resume_token,
		);
		if ( false === $new_resume_token ) {
			global $wpdb;
			throw new UserError( __( 'An error occured while trying to save the draft entry. Database Error: ', 'wp-graphql-gravity-forms' ) . $wpdb->print_error() );
		}

		return $new_resume_token ? (string) $new_resume_token : $resume_token;
	}


	/**
	 * Submits a Gravity Forms form.
	 * Uses GFAPI::submit_form().
	 *
	 * @see https://docs.gravityforms.com/api-functions/#submit-form
	 *
	 * @param integer $form_id .
	 * @param array   $input_values .
	 * @param array   $field_values .
	 * @param integer $target_page .
	 * @param integer $source_page .
	 * @return array
	 *
	 * @throws UserError .
	 */
	public static function submit_form( int $form_id, array $input_values, array $field_values = [], int $target_page = 0, int $source_page = 0 ) : array {
		$submission = GFAPI::submit_form(
			$form_id,
			$input_values,
			$field_values,
			$target_page,
			$source_page,
		);

		if ( is_wp_error( $submission ) ) {
			throw new UserError( __( 'There was an error while processing the form. Error: ', 'wp-graphql-gravity-forms' ) . $submission->get_error_message() );
		}

		return $submission;
	}
}
