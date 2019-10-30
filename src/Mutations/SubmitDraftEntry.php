<?php

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;

/**
 * Submit a Gravity Forms draft entry so that it becomes a permanent entry.
 */
class SubmitDraftEntry implements Hookable, Mutation {
    /**
     * Mutation name.
     */
    const NAME = 'submitGravityFormsDraftEntry';

    /**
     * EntryDataManipulator instance.
     */
    private $entry_data_manipulator;

    public function __construct( EntryDataManipulator $entry_data_manipulator ) {
        $this->entry_data_manipulator = $entry_data_manipulator;
    }

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_mutation' ] );
	}

	public function register_mutation() {
		register_graphql_mutation( self::NAME, [
            'inputFields'         => $this->get_input_fields(),
			'outputFields'        => $this->get_output_fields(),
			'mutateAndGetPayload' => $this->mutate_and_get_payload(),
        ] );
	}

	/**
	 * Defines the input field configuration.
	 *
	 * @return array
	 */
	public static function get_input_fields() : array {
		return [
            'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
		];
    }

	/**
	 * Defines the output field configuration.
	 *
	 * @return array
	 */
	public function get_output_fields() : array {
		return [
			'entryId' => [
				'type'        => 'Integer',
				'description' => __( 'The ID of the entry that was created.', 'wp-graphql-gravity-forms' ),
            ],
            'entry' => [
				'type'        => Entry::TYPE,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve' => function( array $payload ) : array {
					return $this->entry_data_manipulator->manipulate( GFAPI::get_entry( $payload['entryId'] ) );
				}
			],
		];
    }

	/**
	 * Defines the data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			if ( empty( $input ) || ! is_array( $input ) || ! isset( $input['resumeToken'] ) ) {
				throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
            }

			$resume_token = sanitize_text_field( $input['resumeToken'] );
			$draft_entry  = $this->get_draft_entry( $resume_token );

			$this->validate_form_id( $draft_entry['form_id'] );

			$entry_id = $this->create_entry( $draft_entry );

            GFFormsModel::delete_draft_submission( $resume_token );
            GFFormsModel::purge_expired_draft_submissions();

			return [ 'entryId' => $entry_id ];
		};
	}

	private function get_draft_entry( string $resume_token ) : array {
		$draft_entry  = GFFormsModel::get_draft_submission_values( $resume_token );

		if ( ! is_array( $draft_entry ) || empty( $draft_entry['form_id'] ) ) {
			throw new UserError( __( 'A draft with this resume token could not be found.', 'wp-graphql-gravity-forms' ) );
		}

		return $draft_entry;
	}

	private function validate_form_id( int $form_id ) {
		$form_info = GFFormsModel::get_form( $form_id, true );

		if ( ! $form_info || ! $form_info->is_active || $form_info->is_trash ) {
			throw new UserError( __( 'The form associated with this entry is nonexistent or inactive.', 'wp-graphql-gravity-forms' ) );
		}
	}

	private function create_entry( array $draft_entry ) : int {
		$submission = $this->get_draft_submission( $draft_entry );
		$entry_id   = GFAPI::add_entry( $submission['partial_entry'] );

		if ( is_wp_error( $entry_id ) ) {
			throw new UserError( __( 'An error occurred while trying to submit the draft entry.', 'wp-graphql-gravity-forms' ) . ' ' . $entry_id->get_error_message() );
		}

		return $entry_id;
	}

	private function get_draft_submission( array $draft_entry ) : array {
		$submission = json_decode( $draft_entry['submission'], true );

		if ( ! $submission ) {
			throw new UserError( __( 'The submission data for this draft entry could not be read.', 'wp-graphql-gravity-forms' ) );
		}

		return $submission;
	}
}
