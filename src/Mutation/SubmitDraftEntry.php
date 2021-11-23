<?php
/**
 * Mutation - submitGravityFormsDraftEntry
 *
 * Registers mutation to submit a Gravity Forms draft entry so that it becomes a permanent entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.0.1
 * @since 0.3.0 Support post creation.
 */

namespace WPGraphQL\GF\Mutation;

use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\DataManipulators\EntryDataManipulator;
use WPGraphQL\GF\Type\WPObject\Entry\Entry;
use WPGraphQL\GF\Type\WPObject\FieldError;
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
	public static $name = 'submitGravityFormsDraftEntry';

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields() : array {
		return [
			'resumeToken' => [
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields() : array {
		return [
			'entryId' => [
				'type'        => 'Int',
				'description' => __( 'The ID of the entry that was created.', 'wp-graphql-gravity-forms' ),
			],
			'entry'   => [
				'type'        => Entry::$type,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload ) {
					if ( ! empty( $payload['errors'] ) ) {
						return null;
					}

					$entry = GFUtils::get_entry( $payload['entryId'] );

					return EntryDataManipulator::manipulate( $entry );
				},
			],
			'errors'  => [
				'type'        => [ 'list_of' => FieldError::$type ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			static::check_required_inputs( $input );

			$resume_token = sanitize_text_field( $input['resumeToken'] );
			$submission   = GFUtils::get_draft_submission( $resume_token );
			$form_id      = $submission['partial_entry']['form_id'];

			$form = GFUtils::get_form( $form_id );

			$submission['page_number'] = GFUtils::get_last_form_page( $form );

			add_filter( 'gform_field_validation', [ FormSubmissionHelper::class, 'disable_validation_for_unsupported_fields' ], 10, 4 );
			$result = GFUtils::submit_form(
				$form_id,
				$submission['field_values'], // $input_values,
				$submission['field_values'],
			);
			remove_filter( 'gform_field_validation', [ FormSubmissionHelper::class, 'disable_validation_for_unsupported_fields' ] );

			if ( ! empty( $result['entry_id'] ) ) {
				GFFormsModel::delete_draft_submission( $resume_token );
				GFFormsModel::purge_expired_draft_submissions();
			}

			return [
				'entryId' => ! empty( $result['entry_id'] ) ? absint( $result['entry_id'] ) : null,
				'errors'  => isset( $result['validation_messages'] ) ? FormSubmissionHelper::get_submission_errors( $result['validation_messages'] ) : null,
			];
		};
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	protected static function check_required_inputs( $input ) : void {
		parent::check_required_inputs( $input );
		if ( ! isset( $input['resumeToken'] ) ) {
				throw new UserError( __( 'Mutation not processed. The resumeToken must be set.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
