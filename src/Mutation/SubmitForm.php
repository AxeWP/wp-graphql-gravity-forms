<?php
/**
 * Mutation - submitGfForm
 *
 * Submits a Gravity Forms form.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.4.0
 */

namespace WPGraphQL\GF\Mutation;

use GFAPI;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\Input\FormFieldValuesInput;
use WPGraphQL\GF\Type\Input\SubmitFormMetaInput;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPObject\FieldError;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - SubmitForm
 */
class SubmitForm extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'submitGfForm';

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields() : array {
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
	public static function get_output_fields() : array {
		return [
			'entry'     => [
				'type'        => Entry::$type,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload, array $args, AppContext $context ) {
					if ( ! empty( $payload['errors'] ) || ( ! $payload['entryId'] && ! $payload['resumeToken'] ) ) {
						return null;
					}
					if ( ! empty( $payload['resumeToken'] ) ) {
						return Factory::resolve_draft_entry( $payload['resumeToken'], $context );
					}

					if ( ! empty( $payload['entryId'] ) ) {
						return Factory::resolve_entry( $payload['entryId'], $context );
					}
				},
			],
			'errors'    => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
			'resumeUrl' => [
				'type'        => 'String',
				'description' => __( 'Draft resume URL. Null if submitting an entry. If the "Referer" header is not included in the request, this will be an empty string.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			// Get the form database_id.
			$form_id = self::get_form_id_from_id( $input['id'] );

			$form = GFUtils::get_form( $form_id );

			// Set default values.
			$target_page   = isset( $input['targetPage'] ) ? absint( $input['targetPage'] ) : 0;
			$source_page   = isset( $input['sourcePage'] ) ? absint( $input['sourcePage'] ) : 0;
			$save_as_draft = ! empty( $input['saveAsDraft'] );

			// Initialize $_FILES with fileupload inputs.
			self::initialize_files( $form );

			$field_values = self::prepare_field_values( $input['fieldValues'], $form );

			add_filter( 'gform_field_validation', [ FormSubmissionHelper::class, 'disable_validation_for_unsupported_fields' ], 10, 4 );
			$submission = GFUtils::submit_form(
				$input['id'],
				self::get_input_values(
					$save_as_draft,
					$field_values,
					GFFormsModel::$uploaded_files[ $input['id'] ] ?? []
				),
				$field_values,
				$target_page,
				$source_page,
			);
			remove_filter( 'gform_field_validation', [ FormSubmissionHelper::class, 'disable_validation_for_unsupported_fields' ] );

			$entry_data = self::prepare_entry_data( $input );

			if ( $submission['is_valid'] ) {
				self::update_entry_properties( $form, $submission, $entry_data );
			}

			return [
				'entryId'     => ! empty( $submission['entry_id'] ) ? absint( $submission['entry_id'] ) : null,
				'resumeToken' => $submission['resume_token'] ?? null,
				'resumeUrl'   => isset( $submission['resume_token'] ) ? GFUtils::get_resume_url( $submission['resume_token'], $entry_data['source_url'] ?? '', $form ) : null,
				'errors'      => isset( $submission['validation_messages'] ) ? FormSubmissionHelper::get_submission_errors( $submission['validation_messages'] ) : null,
			];
		};
	}

	/**
	 * Prepares draft entry object for update.
	 *
	 * @param array $input .
	 */
	private static function prepare_entry_data( array $input ) : array {
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
			$data['ip'] = GFUtils::get_ip( $input['entryMeta']['sourceUrl'] );
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
	 * {@inheritDoc}
	 */
	private static function prepare_field_values( array $field_values, array $form ) : array {
		$formatted_values = [];

		// Prepares field values to a format GF can understand.
		foreach ( $field_values as $values ) {
			$field = GFUtils::get_field_by_id( $form, $values['id'] );

			$value = FormSubmissionHelper::prepare_single_field_value( $values, $field );

			// Add values to array based on field type.
			$formatted_values = FormSubmissionHelper::add_value_to_array( $formatted_values, $field, $value );
		}

		return FormSubmissionHelper::rename_keys_for_field_values( $formatted_values );
	}

	/**
	 * Updates entry properties that cannot be set with GFAPI::submit_form().
	 *
	 * @param array $form .
	 * @param array $submission The Gravity Forms submission result array.
	 * @param array $entry_meta .
	 * @throws UserError .
	 */
	private static function update_entry_properties( array $form, array $submission, array $entry_meta ) : void {
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
						__( 'Unable to update the entry `%s` property.', 'wp-graphql-gravity-forms' ),
						$key
					)
				);
			}
		}
	}

	/**
	 * Creates the $input_values array required by GFAPI::submit_form().
	 *
	 * @param boolean $is_draft .
	 * @param array   $field_values . Required so submit_form() can generate the $_POST object.
	 * @param array   $file_upload_values .
	 * @return array
	 */
	private static function get_input_values( bool $is_draft, array $field_values, array $file_upload_values ) : array {
		return [
			'gform_save'           => $is_draft,
			'gform_uploaded_files' => wp_json_encode( $file_upload_values ),
		] + $field_values;
	}


	/**
	 * Initializes the $_FILES array with the fileupload `input_{id}`.
	 * This prevents any notices about missing array keys.
	 *
	 * @param array $form .
	 */
	private static function initialize_files( $form ) : void {
		foreach ( $form['fields'] as $field ) {
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
