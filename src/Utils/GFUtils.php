<?php
/**
 * GF Utils
 *
 * Wrappers for common Gravity Forms functions.
 *
 * @package WPGraphQL\GF\Utils
 * @since 0.4.0
 */

declare( strict_types = 1 );

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
	 * @param int  $form_id .
	 * @param bool $active_only Whether to only return the form if it is active.
	 *
	 * @return array<string,mixed> The form object.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_form( int $form_id, bool $active_only = true ): array {
		$form = GFAPI::get_form( $form_id );

		if ( ! is_array( $form ) ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( esc_html__( 'Unable to retrieve the form for the given ID %s.', 'wp-graphql-gravity-forms' ), absint( $form_id ) ),
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
				sprintf( esc_html__( 'The form for the given ID %s is inactive or trashed.', 'wp-graphql-gravity-forms' ), absint( $form_id ) ),
			);
		}

		/**
		 * @var array<string,mixed> $form
		 */
		return $form;
	}

	/**
	 * Returns all the form objects.
	 *
	 * Based on GFAPI::get_forms.
	 *
	 * @see https://docs.gravityforms.com/api-functions/#get-forms
	 *
	 * @param int[]  $ids         Array of form ids to limit results. Empty if all.
	 * @param bool   $active      True if active forms are returned. False to get inactive forms. Defaults to true.
	 * @param bool   $trash       True if trashed forms are returned. False to exclude trash. Defaults to false.
	 * @param string $sort_column The column to sort the results on.
	 * @param string $sort_dir    The sort direction, ASC or DESC.
	 *
	 * @return array<string,mixed>[] The array of Form Objects.
	 */
	public static function get_forms( array $ids = [], bool $active = true, bool $trash = false, string $sort_column = 'id', string $sort_dir = 'DESC' ): array {
		$form_ids = ! empty( $ids ) ? $ids : GFFormsModel::get_form_ids( $active, $trash, $sort_column, $sort_dir );

		if ( empty( $form_ids ) ) {
			return [];
		}

		$forms = [];

		foreach ( $form_ids as $form_id ) {
			$forms[] = self::get_form( (int) $form_id, false );
		}

		return $forms;
	}

	/**
	 * Gets the last page of the form. Useful for form submissions.
	 *
	 * @param array<string,mixed> $form The form object.
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
	 * @param array<string,mixed> $form     The form.
	 * @param int                 $field_id Field ID.
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
				sprintf( esc_html__( 'The form (ID %1$s) does not contain a field with the field ID %2$s.', 'wp-graphql-gravity-forms' ), esc_html( $form['id'] ), absint( $field_id ) )
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
	 * @param int $entry_id .
	 *
	 * @return array<int|string,mixed> The entry object.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_entry( int $entry_id ): array {
		$entry = GFAPI::get_entry( $entry_id );

		if ( $entry instanceof \WP_Error ) {
			throw new UserError(
				// translators: Gravity Forms form id.
				sprintf( esc_html__( 'The entry for the given ID %s was not found. Error: .', 'wp-graphql-gravity-forms' ), absint( $entry_id ) ) . esc_html( $entry->get_error_message() )
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
	 * @param array<int|string,mixed> $entry_data .
	 * @param int                     $entry_id .
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function update_entry( array $entry_data, ?int $entry_id = null ): int {
		$entry_id = $entry_id ?? (int) $entry_data['id'];

		$is_entry_updated = GFAPI::update_entry( $entry_data, $entry_id );

		if ( $is_entry_updated instanceof \WP_Error ) {
			throw new UserError(
				// translators: Gravity Forms entry id.
				sprintf( esc_html__( 'An error occured while trying to update the entry (ID: %s). Error: .', 'wp-graphql-gravity-forms' ), esc_html( (string) $entry_data['id'] ) ) . esc_html( $is_entry_updated->get_error_message() )
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
	 * @return array<int|string,mixed> Draft entry.
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_draft_entry( string $resume_token ): array {
		$draft_entry = GFFormsModel::get_draft_submission_values( $resume_token );

		if ( ! is_array( $draft_entry ) || empty( $draft_entry ) ) {
			throw new UserError(
				// translators: Gravity Forms form id and field id.
				sprintf( esc_html__( 'A draft entry with the resume token %s could not be found.', 'wp-graphql-gravity-forms' ), esc_html( $resume_token ) )
			);
		}

		return $draft_entry;
	}

	/**
	 * Returns draft entry submittion data.
	 *
	 * @param string $resume_token Draft entry resume token.
	 *
	 * @return array<int|string,mixed> Draft entry submission data.
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function get_draft_submission( string $resume_token ): array {
		$draft_entry = self::get_draft_entry( $resume_token );

		$submission = json_decode( $draft_entry['submission'], true );

		if ( ! $submission ) {
			throw new UserError(
					// translators: Gravity Forms form id and field id.
				sprintf( esc_html__( 'The draft entry submission data for the resume token %s could not be read.', 'wp-graphql-gravity-forms' ), esc_html( $resume_token ) )
			);
		}

		// Sets resume token to partial_entry.
		$submission['partial_entry']['resumeToken'] = $resume_token;

		return $submission;
	}

	/**
	 * Get the draft resume URL.
	 *
	 * @param string              $resume_token Resume token.
	 * @param string              $source_url   Source URL.
	 * @param array<string,mixed> $form         Form object.
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
		 * @param array<string,mixed> $form         The Form Object.
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
	 * @param array<string,mixed>     $form .
	 * @param array<int|string,mixed> $entry .
	 * @param ?array<string,mixed>    $field_values .
	 * @param int                     $page_number .
	 * @param mixed[]                 $files .
	 * @param string                  $form_unique_id .
	 * @param string                  $ip .
	 * @param string                  $source_url .
	 * @param string                  $resume_token .
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	public static function save_draft_submission( array $form, array $entry, ?array $field_values = null, int $page_number = 1, array $files = [], ?string $form_unique_id = null, string $ip = '', string $source_url = '', string $resume_token = '' ): string {
		if ( empty( $form ) || empty( $entry ) ) {
			throw new UserError( esc_html__( 'An error occured while trying to save the draft entry. Form or Entry not set.', 'wp-graphql-gravity-forms' ) );
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
			throw new UserError( esc_html__( 'An error occured while trying to save the draft entry. Database Error: .', 'wp-graphql-gravity-forms' ) . esc_html( $wpdb->print_error() ) );
		}

		return $new_resume_token ? (string) $new_resume_token : $resume_token;
	}

	/**
	 * Submits a Gravity Forms form.
	 * Uses GFAPI::submit_form().
	 *
	 * @see https://docs.gravityforms.com/api-functions/#submit-form
	 *
	 * @param int                 $form_id .
	 * @param array<string,mixed> $input_values .
	 * @param array<string,mixed> $field_values .
	 * @param int                 $target_page .
	 * @param int                 $source_page .
	 *
	 * @return array<int|string,mixed> The submission object.
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

		// Cleanup GF state.
		unset( $_POST );
		\GFFormsModel::flush_current_lead();
		\GFFormDisplay::$submission = [];

		if ( $submission instanceof \WP_Error ) {
			throw new UserError( esc_html( $submission->get_error_message() ) );
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
	 * @return array<string,mixed> GF uploads dir config.
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
				throw new UserError( esc_html__( 'Failed to upload file. The Gravity Forms Upload directory could not be created.', 'wp-graphql-gravity-forms' ) );
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
}
