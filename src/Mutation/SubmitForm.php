<?php
/**
 * Mutation - submitGfForm
 *
 * Submits a Gravity Forms form.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Mutation;

use GFAPI;
use GraphQL\Error\UserError;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\EntryObjectMutation;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Model\SubmittedEntry;
use WPGraphQL\GF\Type\Input\FormFieldValuesInput;
use WPGraphQL\GF\Type\Input\SubmitFormMetaInput;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPObject\FieldError;
use WPGraphQL\GF\Type\WPObject\SubmissionConfirmation;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - SubmitForm
 */
class SubmitForm extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'SubmitGfForm';

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields(): array {
		return [
			'id'          => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'The form ID. Accepts either a global or Database ID.', 'wp-graphql-gravity-forms' ),
			],
			'entryMeta'   => [
				'type'        => SubmitFormMetaInput::$type,
				'description' => __( 'The entry meta associated with the submission.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues' => [
				'type'        => [ 'non_null' => [ 'list_of' => FormFieldValuesInput::$type ] ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'saveAsDraft' => [
				'type'        => 'Boolean',
				'description' => __( 'Set to `true` if submitting a draft entry. Defaults to `false`.', 'wp-graphql-gravity-forms' ),
			],
			'sourcePage'  => [
				'type'        => 'Int',
				'description' => __( 'Useful for multi-page forms to indicate which page of the form was just submitted.', 'wp-graphql-gravity-forms' ),
			],
			'targetPage'  => [
				'type'        => 'Int',
				'description' => __( 'Useful for multi-page forms to indicate which page is to be loaded if the current page passes validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields(): array {
		return [
			'confirmation'     => [
				'type'        => SubmissionConfirmation::$type,
				'description' => __( 'The form confirmation data. Null if the submission has `errors`', 'wp-graphql-gravity-forms' ),
			],
			'entry'            => [
				'type'        => Entry::$type,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( array $payload, array $args, AppContext $context ) {
					// Return early if bad or nonexistent entry.
					if ( ! empty( $payload['errors'] ) || ( ! $payload['entryId'] && ! $payload['resumeToken'] ) ) {
						return null;
					}

					if ( ! empty( $payload['resumeToken'] ) ) {
						return Factory::resolve_draft_entry( $payload['resumeToken'], $context );
					}

					if ( ! empty( $payload['entryId'] ) ) {
						/**
						 * Allow non-authenticated users to view their own entries.
						 *
						 * The callback checks if the model is for the current id, and then it's applied
						 * to the model as a filter.
						 */
						$is_private_callback = static function ( bool $can_view, int $form_id, $entry_id ) use ( $payload ) {
							if ( $payload['entryId'] === $entry_id ) {
								return true;
							}

							return $can_view;
						};
						add_filter( 'graphql_gf_can_view_entries', $is_private_callback, 10, 3 );

						// Create the model directly, since the filter will be removed by the time Deferred would resolve.
						$entry       = GFUtils::get_entry( $payload['entryId'] );
						$entry_model = new SubmittedEntry( $entry );

						remove_filter( 'graphql_gf_can_view_entries', $is_private_callback, 10 );

						return $entry_model;
					}
				},
			],
			'errors'           => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
			'resumeUrl'        => [
				'type'        => 'String',
				'description' => __( 'Draft resume URL. Null if submitting an entry. If the "Referer" header is not included in the request, this will be an empty string.', 'wp-graphql-gravity-forms' ),
			],
			'targetPageNumber' => [
				'type'        => 'Int',
				'description' => __( 'The page number of the form that should be displayed after submission. This will be different than the `targetPage` provided to the mutation if a field on a previous field failed validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload(): callable {
		return static function ( $input ): array {
			// Get the form database_id.
			$form_id = Utils::get_form_id_from_id( $input['id'] );

			$form = GFUtils::get_form( $form_id );

			// Set default values.
			$target_page   = isset( $input['targetPage'] ) ? absint( $input['targetPage'] ) : 0;
			$source_page   = isset( $input['sourcePage'] ) ? absint( $input['sourcePage'] ) : 0;
			$save_as_draft = ! empty( $input['saveAsDraft'] );

			$field_values = self::prepare_field_values( $input['fieldValues'], $form, $save_as_draft );

			$files        = EntryObjectMutation::initialize_files( $form['fields'], $input['fieldValues'], $save_as_draft );
			$input_values = self::get_input_values( $save_as_draft, $field_values, $files );

			$submission = GFUtils::submit_form(
				(int) $input['id'],
				$input_values,
				$field_values,
				$target_page,
				$source_page,
			);

			$entry_data = self::prepare_entry_data( $input );

			if ( $submission['is_valid'] ) {
				self::update_entry_properties( $form, $submission, $entry_data );
			}
			return [
				'confirmation'     => isset( $submission['confirmation_type'] ) ? EntryObjectMutation::get_submission_confirmation( $submission ) : null,
				'entryId'          => ! empty( $submission['entry_id'] ) ? absint( $submission['entry_id'] ) : null,
				'errors'           => isset( $submission['validation_messages'] ) ? EntryObjectMutation::get_submission_errors( $submission['validation_messages'], $form_id ) : null,
				'form_id'          => $form_id,
				'resumeToken'      => $submission['resume_token'] ?? null,
				'resumeUrl'        => isset( $submission['resume_token'] ) ? GFUtils::get_resume_url( $submission['resume_token'], $entry_data['source_url'] ?? '', $form ) : null,
				'submission'       => $submission,
				'targetPageNumber' => self::get_target_page_number( $target_page, $submission ),
			];
		};
	}

	/**
	 * Prepares draft entry object for update.
	 *
	 * @param array<string|int,mixed> $input The input values.
	 *
	 * @return array{created_by?:int,date_created?:string,ip?:string,source_url?:string,user_agent?:string}
	 */
	private static function prepare_entry_data( array $input ): array {
		$data = [];

		// Update Created by id.
		if ( isset( $input['entryMeta']['createdById'] ) ) {
			$data['created_by'] = absint( $input['entryMeta']['createdById'] );
		}

		// Update Date created.
		if ( isset( $input['entryMeta']['dateCreatedGmt'] ) ) {
			$data['date_created'] = sanitize_text_field( $input['entryMeta']['dateCreatedGmt'] );
		}
		// Update IP created.
		if ( isset( $input['entryMeta']['ip'] ) ) {
			$data['ip'] = GFUtils::get_ip( $input['entryMeta']['ip'] );
		}

		// Update source url.
		if ( isset( $input['entryMeta']['sourceUrl'] ) ) {
			$data['source_url'] = $input['entryMeta']['sourceUrl'];
		}

		// Update user agent.
		if ( isset( $input['entryMeta']['userAgent'] ) ) {
			$data['user_agent'] = $input['entryMeta']['userAgent'];
		}

		return $data;
	}

	/**
	 * Converts the provided field values into a format that Gravity Forms can understand.
	 *
	 * @param array<string,mixed>[] $field_values The field values.
	 * @param array<string,mixed>   $form The form object.
	 * @param bool                  $save_as_draft Whether to save the submission as a draft.
	 *
	 * @return array<string,mixed>
	 */
	private static function prepare_field_values( array $field_values, array $form, bool $save_as_draft ): array {
		$formatted_values = [];

		// Prepares field values to a format GF can understand.
		foreach ( $field_values as $values ) {
			$field_value_input = EntryObjectMutation::get_field_value_input( $values, $form, $save_as_draft );

			$field_value_input->add_value_to_submission( $formatted_values );
		}

		return EntryObjectMutation::rename_field_names_for_submission( $formatted_values );
	}

	/**
	 * Updates entry properties that cannot be set with GFAPI::submit_form().
	 *
	 * @param array<string,mixed>     $form The Gravity Forms form array.
	 * @param array<int|string,mixed> $submission The Gravity Forms submission result array.
	 * @param array<string,mixed>     $entry_meta The entry meta data.
	 * @throws \GraphQL\Error\UserError .
	 */
	private static function update_entry_properties( array $form, array $submission, array $entry_meta ): void {
		if ( ! empty( $submission['resume_token'] ) ) {
			$decoded_submission = GFUtils::get_draft_submission( $submission['resume_token'] );

			$decoded_submission['partial_entry'] = array_replace( $decoded_submission['partial_entry'], $entry_meta );

			GFUtils::save_draft_submission(
				$form,
				$decoded_submission['partial_entry'],
				$decoded_submission['field_values'],
				$decoded_submission['page_number'] ?? 1,
				$decoded_submission['files'] ?? [],
				$decoded_submission['gform_unique_id'] ?? null,
				$decoded_submission['partial_entry']['ip'] ?? '',
				$decoded_submission['partial_entry']['source_url'] ?? '',
				$submission['resume_token'],
			);

			return;
		}

		if ( empty( $submission['entry_id'] ) ) {
			return;
		}

		foreach ( $entry_meta as $key => $value ) {
			$is_updated = GFAPI::update_entry_property( $submission['entry_id'], $key, $value );

			if ( false === $is_updated ) {
				throw new UserError(
					sprintf(
						// translators: Gravity Forms entry property.
						esc_html__( 'Unable to update the entry `%s` property.', 'wp-graphql-gravity-forms' ),
						esc_html( $key )
					)
				);
			}
		}
	}

	/**
	 * Creates the $input_values array required by GFAPI::submit_form().
	 *
	 * @param bool                $is_draft .
	 * @param array<string,mixed> $field_values Required so submit_form() can generate the $_POST object.
	 * @param array<string,mixed> $file_upload_values .
	 *
	 * @return array<string,mixed>
	 */
	private static function get_input_values( bool $is_draft, array $field_values, array $file_upload_values ): array {
		$input_values = [
			'gform_save' => $is_draft,
		];

		if ( ! empty( $file_upload_values ) ) {
			$input_values['gform_uploaded_files'] = wp_json_encode( $file_upload_values );
		}

		return $input_values + $field_values;
	}

	/**
	 * Get the target page number use to resolve the mutation.
	 *
	 * @param int                     $original_target_page The original target page number.
	 * @param array<int|string,mixed> $submission The Gravity Forms submission result array.
	 */
	private static function get_target_page_number( int $original_target_page, array $submission ): ?int {
		// Valid Draft submissions should pass through to the original target page.
		if ( ! empty( $submission['resume_token'] ) && ! empty( $submission['is_valid'] ) ) {
			return ! empty( $original_target_page ) ? $original_target_page : null;
		}

		// Regular submissions should return the target page.
		// In draft submissions, the target page is the source page, so this will work with invalid submissions.
		return ! empty( $submission['page_number'] ) ? (int) $submission['page_number'] : null;
	}
}
