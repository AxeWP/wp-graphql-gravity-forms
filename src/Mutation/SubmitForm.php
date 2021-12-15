<?php
/**
 * Mutation - submitGravityFormsForm
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
use WPGraphQL\GF\Type\WPObject\Entry\Entry;
use WPGraphQL\GF\Type\WPObject\FieldError;
use WPGraphQL\GF\Type\Input\FormFieldValuesInput;
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
	public static $name = 'submitGravityFormsForm';

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields() : array {
		return [
			'createdBy'   => [
				'type'        => 'Int',
				'description' => __( 'Optional. ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues' => [
				'type'        => [ 'list_of' => FormFieldValuesInput::$type ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'formId'      => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'The form ID.', 'wp-graphql-gravity-forms' ),
			],
			'ip'          => [
				'type'        => 'String',
				'description' => __( 'Optional. The IP address of the user who submitted the draft entry. Default is an empty string.', 'wp-graphql-gravity-forms' ),
			],
			'saveAsDraft' => [
				'type'        => 'Boolean',
				'description' => __( 'Optional. Set to `true` if submitting a draft entry.', 'wp-graphql-gravity-forms' ),
			],
			'sourcePage'  => [
				'type'        => 'Int',
				'description' => __( 'Optional. Useful for multi-page forms to indicate which page of the form was just submitted.', 'wp-graphql-gravity-forms' ),
			],
			'sourceUrl'   => [
				'type'        => 'String',
				'description' => __( 'Optional. Used to overwrite the sourceUrl the form was submitted from.', 'wp-graphql-gravity-forms' ),
			],
			'targetPage'  => [
				'type'        => 'Int',
				'description' => __( 'Optional. Useful for multi-page forms to indicate which page is to be loaded if the current page passes validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields() : array {
		return [
			'entryId'     => [
				'type'        => 'Int',
				'description' => __( 'The ID of the entry that was created. Null if the entry was only partially submitted or submitted as a draft.', 'wp-graphql-gravity-forms' ),
			],
			'entry'       => [
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
			'errors'      => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
			'resumeUrl'   => [
				'type'        => 'String',
				'description' => __( 'Draft resume URL. If the "Referer" header is not included in the request, this will be an empty string.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			// Check for required fields.
			static::check_required_inputs( $input );

			$form = GFUtils::get_form( $input['formId'] );

			// Set default values.
			$target_page   = $input['targetPage'] ?? 0;
			$source_page   = $input['sourcePage'] ?? 0;
			$save_as_draft = $input['saveAsDraft'] ?? false;
			$ip            = empty( $form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['ip'] ?? '' ) : '';
			$created_by    = isset( $input['createdBy'] ) ? absint( $input['createdBy'] ) : null;
			$source_url    = $input['sourceUrl'] ?? '';

			// Initialize $_FILES with fileupload inputs.
			self::initialize_files( $form );

			$field_values = self::prepare_field_values( $input['fieldValues'], $form );

			add_filter( 'gform_field_validation', [ FormSubmissionHelper::class, 'disable_validation_for_unsupported_fields' ], 10, 4 );
			$submission = GFUtils::submit_form(
				$input['formId'],
				self::get_input_values( $save_as_draft, $field_values, GFFormsModel::$uploaded_files[ $input['formId'] ] ?? [] ),
				$field_values,
				$target_page,
				$source_page,
			);
			remove_filter( 'gform_field_validation', [ FormSubmissionHelper::class, 'disable_validation_for_unsupported_fields' ] );

			if ( $submission['is_valid'] ) {
				self::update_entry_properties( $form, $submission, $ip, $source_url, $created_by );
			}

			return [
				'entryId'     => ! empty( $submission['entry_id'] ) ? absint( $submission['entry_id'] ) : null,
				'resumeToken' => $submission['resume_token'] ?? null,
				'resumeUrl'   => isset( $submission['resume_token'] ) ? GFUtils::get_resume_url( $source_url, $submission['resume_token'], $form ) : null,
				'errors'      => isset( $submission['validation_messages'] ) ? FormSubmissionHelper::get_submission_errors( $submission['validation_messages'] ) : null,
			];
		};
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	protected static function check_required_inputs( $input = null ) : void {
		parent::check_required_inputs( $input );
		if ( ! isset( $input['formId'] ) ) {
			throw new UserError( __( 'Mutation not processed. Form ID not provided.', 'wp-graphql-gravity-forms' ) );
		}
		if ( empty( $input['fieldValues'] ) ) {
			throw new UserError( __( 'Mutation not processed. Field values not provided.', 'wp-graphql-gravity-forms' ) );
		}
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
	 * @param array   $form .
	 * @param array   $submission The Gravity Forms submission result array.
	 * @param string  $ip .
	 * @param string  $source_url .
	 * @param integer $created_by .
	 * @throws UserError .
	 */
	private static function update_entry_properties( array $form, array $submission, string $ip, string $source_url, int $created_by = null ) : void {
		if ( ! empty( $submission['resume_token'] ) ) {
			$draft_entry        = GFUtils::get_draft_entry( $submission['resume_token'] );
			$decoded_submission = json_decode( $draft_entry['submission'], true );

			$ip         = $ip ?: $decoded_submission['partial_entry']['ip'];
			$created_by = $created_by ?: $decoded_submission['partial_entry']['created_by'];
			$source_url = $source_url ?: $decoded_submission['partial_entry']['source_url'];
			$is_updated = GFFormsModel::update_draft_submission( $submission['resume_token'], $form, $draft_entry['date_created'], $ip, $source_url, $draft_entry['submission'] );
			if ( false === $is_updated ) {
				throw new UserError( __( 'Unable to update the draft entry properties.', 'wp-graphql-gravity-forms' ) );
			}
			return;
		}

		if ( empty( $submission['entry_id'] ) ) {
			return;
		}

		if ( ! empty( $ip ) ) {
			$is_updated = GFAPI::update_entry_property( $submission['entry_id'], 'ip', $ip );
			if ( false === $is_updated ) {
				throw new UserError( __( 'Unable to update the entry IP address', 'wp-graphql-gravity-forms' ) );
			}
		}

		if ( null !== $created_by ) {
			$is_updated = GFAPI::update_entry_property( $submission['entry_id'], 'created_by', $created_by );
			if ( false === $is_updated ) {
				throw new UserError( __( 'Unable to update the entry createdBy id.', 'wp-graphql-gravity-forms' ) );
			}
		}

		if ( ! empty( $source_url ) ) {
			$is_updated = GFAPI::update_entry_property( $submission['entry_id'], 'source_url', $source_url );
			if ( false === $is_updated ) {
				throw new UserError( __( 'Unable to update the entry source url', 'wp-graphql-gravity-forms' ) );
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
