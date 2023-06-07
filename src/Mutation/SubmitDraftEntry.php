<?php
/**
 * Mutation - submitGfDraftEntry
 *
 * Registers mutation to submit a Gravity Forms draft entry so that it becomes a permanent entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Mutation;

use GFFormsModel;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\EntryObjectMutation;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\Enum\DraftEntryIdTypeEnum;
use WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry;
use WPGraphQL\GF\Type\WPObject\FieldError;
use WPGraphQL\GF\Type\WPObject\SubmissionConfirmation;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - SubmitDraftEntry
 */
class SubmitDraftEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'submitGfDraftEntry';

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields(): array {
		return [
			'id'     => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'Either the global ID of the draft entry, or its resume token.', 'wp-graphql-gravity-forms' ),
			],
			'idType' => [
				'type'        => DraftEntryIdTypeEnum::$type,
				'description' => __( 'The ID type for the draft entry. Defaults to `ID` .', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields(): array {
		return [
			'confirmation' => [
				'type'        => SubmissionConfirmation::$type,
				'description' => __( 'The form confirmation data. Null if the submission has `errors`', 'wp-graphql-gravity-forms' ),
			],
			'entry'        => [
				'type'        => SubmittedEntry::$type,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( array $payload, array $args, AppContext $context ) {
					if ( ! empty( $payload['errors'] ) || empty( $payload['entryId'] ) ) {
						return null;
					}

					return Factory::resolve_entry( (int) $payload['entryId'], $context );
				},
			],
			'errors'       => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload(): callable {
		return static function ( $input, AppContext $context, ResolveInfo $info ): array {
			// Get the resume token.
			$id_type      = isset( $input['idType'] ) ? $input['idType'] : 'global_id';
			$resume_token = self::get_resume_token_from_id( $input['id'], $id_type );

			// Prepare the entry data.
			$submission = GFUtils::get_draft_submission( $resume_token );
			$form       = GFUtils::get_form( $submission['partial_entry']['form_id'] );

			$submission['page_number'] = GFUtils::get_last_form_page( $form );

			$result = GFUtils::submit_form(
				$submission['partial_entry']['form_id'],
				$submission['field_values'], // $input_values,
				$submission['field_values'],
			);

			if ( ! empty( $result['entry_id'] ) ) {
				GFFormsModel::delete_draft_submission( $resume_token );
				GFFormsModel::purge_expired_draft_submissions();
			}

			return [
				'confirmation' => isset( $result['confirmation_type'] ) ? EntryObjectMutation::get_submission_confirmation( $result ) : null,
				'entryId'      => ! empty( $result['entry_id'] ) ? absint( $result['entry_id'] ) : null,
				'errors'       => isset( $result['validation_messages'] ) ? EntryObjectMutation::get_submission_errors( $result['validation_messages'] ) : null,
			];
		};
	}
}
