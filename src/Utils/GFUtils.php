<?php
/**
 * GF Utils
 *
 * Wrappers for common Gravity Forms functions.
 *
 * @package WPGraphQL\GF\Utils
 * @since 0.4.0
 */

namespace WPGraphQL\GF\Utils;

use GFAPI;
use GFCommon;
use GFFormDisplay;
use GFFormsModel;
use GF_Field;
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
	 */
	public static function get_ip( string $ip ): string {
		return empty( $ip ) ? GFFormsModel::get_ip() : sanitize_text_field( $ip );
	}

	/**
	 * Gets the Gravity Form form object for the given form ID.
	 * Uses GFAPI::get_form().
	 *
	 * @see https://docs.gravityforms.com/api-functions/#get-form
	 *
	 * @param integer $form_id .
	 * @param bool    $active_only Whether to only return the form if it is active.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_form( int $form_id, bool $active_only = true ): array {
		$form = GFAPI::get_form( $form_id );

		if ( ! $form ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( __( 'Unable to retrieve the form for the given ID %s.', 'wp-graphql-gravity-forms' ), $form_id ),
			);
		}

		/**
		 * Filters form object before use.
		 *
		 * @see https://docs.gravityforms.com/gform_pre_render/
		 */
		$form = gf_apply_filters( [ 'gform_pre_render', $form_id ], $form );

		if ( $active_only && ( ! $form['is_active'] || $form['is_trash'] ) ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( __( 'The form for the given ID %s is inactive or trashed.', 'wp-graphql-gravity-forms' ), $form_id ),
			);
		}

		return $form;
	}

	/**
	 * Returns all the form objects.
	 *
	 * Based on GFAPI::get_forms.
	 *
	 * @see https://docs.gravityforms.com/api-functions/#get-forms
	 *
	 * @param array  $ids         Array of form ids to limit results. Empty if all.
	 * @param bool   $active      True if active forms are returned. False to get inactive forms. Defaults to true.
	 * @param bool   $trash       True if trashed forms are returned. False to exclude trash. Defaults to false.
	 * @param string $sort_column The column to sort the results on.
	 * @param string $sort_dir    The sort direction, ASC or DESC.
	 *
	 * @return array The array of Form Objects.
	 */
	public static function get_forms( array $ids = [], bool $active = true, bool $trash = false, string $sort_column = 'id', string $sort_dir = 'DESC' ): array {
		$form_ids = ! empty( $ids ) ? $ids : GFFormsModel::get_form_ids( $active, $trash, $sort_column, $sort_dir );

		if ( empty( $form_ids ) ) {
			return [];
		}

		$forms = [];

		foreach ( $form_ids as $form_id ) {
			$forms[] = self::get_form( $form_id, false );
		}

		return $forms;
	}

	/**
	 * Gets the last page of the form. Useful for form submissions.
	 *
	 * @param array $form .
	 */
	public static function get_last_form_page( array $form ): int {
		require_once GFCommon::get_base_path() . '/form_display.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

		return GFFormDisplay::get_max_page_number( $form );
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::get_form_unique_id() method.
	 *
	 * @param int $form_id Form ID.
	 *
	 * @return string Unique ID.
	 */
	public static function get_form_unique_id( int $form_id ): string {
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
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_field_by_id( array $form, int $field_id ): GF_Field {
		$matching_fields = array_values(
			array_filter(
				$form['fields'],
				static function ( GF_Field $field ) use ( $field_id ): bool {
					return $field['id'] === $field_id;
				}
			)
		);

		if ( empty( $matching_fields ) ) {
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
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_entry( int $entry_id ): array {
		$entry = GFAPI::get_entry( $entry_id );

		if ( is_wp_error( $entry ) ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( __( 'The entry for the given ID %s was not found. Error: .', 'wp-graphql-gravity-forms' ), $entry_id ) . $entry->get_error_message()
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
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function update_entry( array $entry_data, int $entry_id = null ): int {
		$entry_id = $entry_id ?? $entry_data['id'];

		$is_entry_updated = GFAPI::update_entry( $entry_data, $entry_id );

		if ( is_wp_error( ( $is_entry_updated ) ) ) {
			throw new UserError(
				// translators: Gravity Forms entry id.
				sprintf( __( 'An error occured while trying to update the entry (ID: %s). Error: .', 'wp-graphql-gravity-forms' ), $entry_data['id'] ) . $is_entry_updated->get_error_message()
			);
		}

		return $entry_id;
	}

	/**
	 * Returns draft entry array from a given resume token.
	 * Uses GFFormsModel::get_draft_submission_values().
	 *
	 * @param string $resume_token .
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_draft_entry( string $resume_token ): array {
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
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_draft_submission( string $resume_token ): array {
		$draft_entry = self::get_draft_entry( $resume_token );

		$submission = json_decode( $draft_entry['submission'], true );

		if ( ! $submission ) {
			throw new UserError(
					// translators: Gravity Forms form id and field id.
				sprintf( __( 'The draft entry submission data for the resume token %s could not be read.', 'wp-graphql-gravity-forms' ), $resume_token )
			);
		}

		// Sets resume token to partial_entry.
		$submission['partial_entry']['resumeToken'] = $resume_token;

		return $submission;
	}

	/**
	 * Get the draft resume URL.
	 *
	 * @param string     $resume_token Resume token.
	 * @param string     $source_url   Source URL.
	 * @param array|null $form         Form object.
	 *
	 * @return string Resume URL, or empty string if no source URL was provided.
	 */
	public static function get_resume_url( string $resume_token, string $source_url = '', $form = [] ): string {
		if ( empty( $source_url ) ) {
			$source_url = GFFormsModel::get_current_page_url();
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
				'gform_save_and_continue_resume_url', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
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
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function save_draft_submission( array $form, array $entry, array $field_values = null, int $page_number = 1, array $files = [], string $form_unique_id = null, string $ip = '', string $source_url = '', string $resume_token = '' ): string {
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
			throw new UserError( __( 'An error occured while trying to save the draft entry. Database Error: .', 'wp-graphql-gravity-forms' ) . $wpdb->print_error() );
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
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function submit_form( int $form_id, array $input_values, array $field_values = [], int $target_page = 0, int $source_page = 0 ): array {
		$submission = GFAPI::submit_form(
			$form_id,
			$input_values,
			$field_values,
			$target_page,
			$source_page,
		);

		if ( is_wp_error( $submission ) ) {
			throw new UserError( $submission->get_error_message() );
		}

		return $submission;
	}

	/**
	 * Determine appropriate GF form-specific uploads dir config and ensure folders are initiated as needed.
	 *
	 * @see GFFormsModel::get_file_upload_path()
	 *
	 * @since 0.7.0
	 *
	 * @param int $form_id GF form ID.
	 *
	 * @return array     GF uploads dir config.
	 * @throws \GraphQL\Error\UserError If directory doesn't exist or cant be created.
	 */
	public static function get_gravity_forms_upload_dir( int $form_id ): array {
		// Determine YYYY/MM values.
		$time     = current_time( 'mysql' );
		$y        = substr( $time, 0, 4 );
		$m        = substr( $time, 5, 2 );
		$date_dir = DIRECTORY_SEPARATOR . $y . DIRECTORY_SEPARATOR . $m;

		$default_target_root     = GFFormsModel::get_upload_path( $form_id ) . $date_dir;
		$default_target_root_url = GFFormsModel::get_upload_url( $form_id ) . $date_dir;

		// Adding filter to upload root path and url.
		$upload_root_info = [
			'path' => $default_target_root,
			'url'  => $default_target_root_url,
		];
		$upload_root_info = gf_apply_filters( [ 'gform_upload_path', $form_id ], $upload_root_info, $form_id );

		// Determine upload directory.
		$target_root     = $upload_root_info['path'];
		$target_root_url = $upload_root_info['url'];

		$target_root = trailingslashit( $target_root );

		// Create upload directory if it doesnt exist.
		if ( ! is_dir( $target_root ) ) {
			if ( ! wp_mkdir_p( $target_root ) ) {
				throw new UserError( __( 'Failed to upload file. The Gravity Forms Upload directory could not be created.', 'wp-graphql-gravity-forms' ) );
			}

			// Adding index.html files to all subfolders.
			if ( $default_target_root !== $target_root && ! file_exists( $target_root . 'index.html' ) ) {
				GFCommon::recursive_add_index_file( $target_root );
			} elseif ( ! file_exists( GFFormsModel::get_upload_root() . '/index.html' ) ) {
				GFCommon::recursive_add_index_file( GFFormsModel::get_upload_root() );
			} elseif ( ! file_exists( GFFormsModel::get_upload_path( $form_id ) . '/index.html' ) ) {
				GFCommon::recursive_add_index_file( GFFormsModel::get_upload_path( $form_id ) );
			} elseif ( ! file_exists( GFFormsModel::get_upload_path( $form_id ) . sprintf( '/%s/index.html', $y ) ) ) {
				GFCommon::recursive_add_index_file( GFFormsModel::get_upload_path( $form_id ) . sprintf( '/%s', $y ) );
			} else {
				GFCommon::recursive_add_index_file( GFFormsModel::get_upload_path( $form_id ) . sprintf( '/%s/%s', $y, $m ) );
			}
		}

		return [
			'path'    => $target_root,
			'url'     => $target_root_url,
			'subdir'  => $date_dir,
			'basedir' => untrailingslashit( GFFormsModel::get_upload_root() ),
			'baseurl' => untrailingslashit( GFFormsModel::get_upload_url_root() ),
		];
	}

	/**
	 * Handle custom file upload.
	 *
	 * This mimics WP Core upload functionality but allows for uploading file to a custom directory rather than the standard WP uploads dir.
	 * Slightly modified from source.
	 *
	 * @see https://developer.wordpress.org/reference/functions/_wp_handle_upload/
	 *
	 * @author WebDevStudios
	 * @source https://github.com/WebDevStudios/wds-headless-wordpress/blob/5a8e84a2dbb7a0bb537422223ab409ecd2568b00/themes/wds_headless/inc/wp-graphql.php#L452
	 * @param array $file   File data to upload.
	 * @param array $target Target upload directory; WP uploads dir will be used if none provided.
	 *
	 * @return array        Uploaded file data.
	 *
	 * @deprecated 0.11.0
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function handle_file_upload( $file, $target ) {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'GFUtils::handle_file_upload() is deprecated. Please use native WP/GF methods instead.', 'wp-graphql-gravity-forms' ), '0.11.0' );

		$target = $target ?: wp_upload_dir();

		$wp_filetype     = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
		$ext             = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
		$type            = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];
		$proper_filename = empty( $wp_filetype['proper_filename'] ) ? '' : $wp_filetype['proper_filename'];

		// Check to see if wp_check_filetype_and_ext() determined the filename was incorrect.
		if ( ! empty( $proper_filename ) ) {
			$file['name'] = $proper_filename;
		}

		// Return error if file type not allowed.
		if ( ( ! $type || ! $ext ) && ! current_user_can( 'unfiltered_upload' ) ) {
			throw new UserError( __( 'This file type is not permitted for security reasons.', 'wp-graphql-gravity-forms' ) );
		}

		$type = empty( $type ) ? $file['type'] : $type;

		$filename = wp_unique_filename( $target['path'], $file['name'] );

		// Move the file to the GF uploads dir.
		$new_file = $target['path'] . sprintf( '/%s', $filename );

		// Use copy and unlink because rename breaks streams.
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,Generic.PHP.NoSilencedErrors.Forbidden -- duplicating default WP Core functionality.
		$move_new_file = @copy( $file['tmp_name'], $new_file );
		unlink( $file['tmp_name'] ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink

		if ( ! $move_new_file ) {
			throw new UserError( __( 'Failed to copy the file to the server.', 'wp-graphql-gravity-forms' ) );
		}

		// Set correct file permissions.
		$stat = stat( dirname( $new_file ) );
		if ( is_array( $stat ) ) {
			$perms = $stat['mode'] & 0000666;
			chmod( $new_file, $perms ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.chmod_chmod
		}

		// Compute the URL.
		$url = $target['url'] . sprintf( '/%s', $filename );

		return [
			'file' => $new_file,
			'url'  => $url,
			'type' => $type,
		];
	}
}
