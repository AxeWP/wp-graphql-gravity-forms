<?php
/**
 * Mutation - UpdateGfDraftEntry
 *
 * Updates a Gravity Forms draft entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.4.0
 */

namespace WPGraphQL\GF\Mutation;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\EntryObjectMutation;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\Enum\DraftEntryIdTypeEnum;
use WPGraphQL\GF\Type\Input\FormFieldValuesInput;
use WPGraphQL\GF\Type\Input\UpdateDraftEntryMetaInput;
use WPGraphQL\GF\Type\WPObject\Entry\DraftEntry;
use WPGraphQL\GF\Type\WPObject\FieldError;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - UpdateDraftEntry
 */
class UpdateDraftEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateGfDraftEntry';

	/**
	 * Gravity Forms field validation errors.
	 *
	 * @var array
	 */
	protected static array $errors = [];

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields(): array {
		return [
			'id'             => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'Either the global ID of the draft entry, or its resume token.', 'wp-graphql-gravity-forms' ),
			],
			'idType'         => [
				'type'        => DraftEntryIdTypeEnum::$type,
				'description' => __( 'The ID type for the draft entry. Defaults to `ID` .', 'wp-graphql-gravity-forms' ),
			],
			'entryMeta'      => [
				'type'        => UpdateDraftEntryMetaInput::$type,
				'description' => __( 'The entry meta values to update.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues'    => [
				'type'        => [ 'list_of' => FormFieldValuesInput::$type ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'shouldValidate' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the field values should be validated on submission. Defaults to false.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields(): array {
		return [
			'draftEntry' => [
				'type'        => DraftEntry::$type,
				'description' => __( 'The draft entry after the update mutation has been applied. If a validation error occurred, the draft entry will NOT have been updated with the invalid value provided.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( array $payload, array $args, AppContext $context ) {
					if ( ! empty( $payload['errors'] ) || empty( $payload['resumeToken'] ) ) {
						return null;
					}
					return Factory::resolve_draft_entry( $payload['resumeToken'], $context );
				},
			],
			'errors'     => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field validation errors.', 'wp-graphql-gravity-forms' ),
			],
			'resumeUrl'  => [
				'type'        => 'String',
				'description' => __( 'Draft resume URL. If the "Referer" header is not included in the request, this will be an empty string.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload(): callable {
		return static function ( $input, AppContext $context, ResolveInfo $info ): array {
			// Check for required fields.
			static::check_required_inputs( $input );

			// Get the resume token.
			$id_type      = isset( $input['idType'] ) ? $input['idType'] : 'global_id';
			$resume_token = self::get_resume_token_from_id( $input['id'], $id_type );

			// Prepare the entry data.
			$submission = GFUtils::get_draft_submission( $resume_token );
			$form       = GFUtils::get_form( $submission['partial_entry']['form_id'] );

			$entry_data = self::prepare_draft_entry_data( $input, $submission, $form );

			// Return early if field errors.
			if ( ! empty( $entry_data['errors'] ) ) {
				return $entry_data;
			}

			$entry_data['resumeToken'] = $resume_token;

			// Update the entry.
			$resume_token = GFUtils::save_draft_submission( ...array_values( $entry_data ) );

			return [
				'resumeToken' => $resume_token,
				'resumeUrl'   => GFUtils::get_resume_url( $resume_token, $entry_data['source_url'], $form ),
			];
		};
	}

	/**
	 * Prepares draft entry object for update.
	 *
	 * @param array $input .
	 * @param array $submission .
	 * @param array $form .
	 */
	private static function prepare_draft_entry_data( array $input, array $submission, array $form ): array {
		$should_validate = isset( $input['shouldValidate'] ) ? (bool) $input['shouldValidate'] : true;

		// Update field values.
		if ( ! empty( $input['fieldValues'] ) ) {
			$values = self::prepare_field_values( $input['fieldValues'], $form, $submission['partial_entry'], $submission, $should_validate );
			if ( ! empty( self::$errors ) ) {
				return [ 'errors' => self::$errors ];
			}

			$submission['partial_entry'] = array_replace( $submission['partial_entry'], $values );
			$submission['field_values']  = array_replace( $submission['field_values'] ?? [], EntryObjectMutation::rename_field_names_for_submission( $values ) );
		}

		// Update CreatedBy ID.
		if ( isset( $input['entryMeta']['createdById'] ) ) {
			$submission['partial_entry']['created_by'] = absint( $input['entryMeta']['createdById'] );
		}
				// Update Date created.
		if ( isset( $input['entryMeta']['dateCreatedGmt'] ) ) {
			$submission['partial_entry']['date_created'] = absint( $input['entryMeta']['dateCreatedGmt'] );
		}

		// Update IP Address.
		if ( isset( $input['entryMeta']['ip'] ) ) {
			$ip                                = empty( $form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['entryMeta']['ip'] ?? '' ) : '';
			$submission['partial_entry']['ip'] = ! empty( $ip ) ? sanitize_text_field( $ip ) : $submission['partial_entry']['ip'];
		}

		// Update Source Url.
		if ( isset( $input['entryMeta']['sourceUrl'] ) ) {
			$submission['partial_entry']['source_url'] = sanitize_text_field( $input['entryMeta']['sourceUrl'] );
		}

		// Update user agent.
		if ( isset( $input['entryMeta']['userAgent'] ) ) {
			$submission['partial_entry']['user_agent'] = sanitize_text_field( $input['entryMeta']['userAgent'] );
		}

		return [
			'form'            => $form,
			'partial_entry'   => $submission['partial_entry'],
			'field_values'    => $submission['field_values'],
			'page_number'     => $submission['page_number'] ?? 1, // @todo: Maybe get from request.
			'files'           => $submission['files'] ?? [],
			'gform_unique_id' => $submission['gform_unique_id'] ?? null,
			'ip'              => $submission['partial_entry']['ip'] ?? '',
			'source_url'      => $submission['partial_entry']['source_url'] ?? '',
		];
	}

	/**
	 * Converts the provided field values into a format that Gravity Forms can understand.
	 *
	 * @param array $field_values .
	 * @param array $form .
	 * @param array $entry .
	 * @param array $submission .
	 * @param bool  $should_validate .
	 */
	private static function prepare_field_values( array $field_values, array $form, array $entry, array &$submission, bool $should_validate ): array {
		$formatted_values = [];

		foreach ( $field_values as $values ) {
			$field_value_input = EntryObjectMutation::get_field_value_input( $values, $form, true, $entry );

			if ( $should_validate ) {
				$field_value_input->validate_value( self::$errors );
			}

			// Add field values to submitted values.
			$submission['submitted_values'][ $values['id'] ] = $field_value_input->value;

			$field_value_input->add_value_to_submission( $formatted_values );
		}

		return $formatted_values;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \GraphQL\Error\UserError .
	 */
	protected static function check_required_inputs( $input = null ): void {
		if ( empty( $input['entryMeta'] ) && empty( $input['fieldValues'] ) ) {
			throw new UserError( __( 'Mutation not processed. No data provided to update.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
