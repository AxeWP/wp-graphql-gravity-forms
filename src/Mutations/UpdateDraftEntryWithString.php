<?php

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;

/**
 * Update a draft Gravity Forms entry with a string value.
 */
class UpdateDraftEntryWithString implements Hookable, Mutation {
    /**
     * Type registered in WPGraphQL.
     */
	const TYPE = 'updateGravityFormsDraftEntryWithString';

	/**
	 * The ID of the field to be updated.
	 *
	 * @var int
	 */
	private $field_id;

	/**
	 * The value to update the field with.
	 *
	 * @var string
	 */
	private $value;

    public function register_hooks() {
		add_action( 'graphql_register_types',           [ $this, 'register_mutation' ] );
		add_filter( 'gform_submission_values_pre_save', [ $this, 'add_submitted_value' ] );
	}

	public function register_mutation() {
		register_graphql_mutation( self::TYPE, [
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
			'fieldId' => [
				'type'        => 'Integer',
				'description' => __( 'Field ID.', 'wp-graphql-gravity-forms' ),
			],
			'value' => [
				'type'        => 'String',
				'description' => __( 'The value as a string.', 'wp-graphql-gravity-forms' ),
			],
		];
    }

	/**
	 * Defines the mutation output field configuration.
	 *
	 * @return array
	 */
	public function get_output_fields() : array {
		return [
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
			// 'fieldValues' => [
			// 	'type'        => '',
			// 	'description' => __( 'The updated draft field values.', 'wp-graphql-gravity-forms' ),
			// ],
		];
    }

	/**
	 * Defines the mutation data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			if ( empty( $input ) || ! is_array( $input ) || ! isset( $input['resumeToken'], $input['fieldId'], $input['value'] ) ) {
				throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
			}

			$resume_token = sanitize_text_field( $input['resumeToken'] );
			$draft_entry  = GFFormsModel::get_draft_submission_values( $resume_token );

			if ( ! is_array( $draft_entry ) || empty( $draft_entry['form_id'] ) ) {
				throw new UserError( __( 'A draft with this resume token could not be found.', 'wp-graphql-gravity-forms' ) );
			}

			$form = GFAPI::get_form( $draft_entry['form_id'] );
			// $form_info = GFFormsModel::get_form( $draft_entry['form_id'], true );

            if ( ! $form || ! $form['is_active'] || $form['is_trash'] ) {
                throw new UserError( __( 'The form associated with this entry is nonexistent or inactive.', 'wp-graphql-gravity-forms' ) );
			}

			$this->field_id = absint( $input['fieldId'] );
			$this->value    = sanitize_text_field( $input['value'] );
			$field          = $this->get_field_by_id( $form );

			if ( ! $field ) {
                throw new UserError( __( 'The form associated with this entry does not contain a field with the field ID provided.', 'wp-graphql-gravity-forms' ) );
			}

			$field->validate( $this->value, $form );

			if ( $field->failed_validation ) {
				$test = __( 'An invalid value was provided.', 'wp-graphql-gravity-forms' ) . ' ' . $field->validation_message;
				throw new UserError( __( 'An invalid value was provided.', 'wp-graphql-gravity-forms' ) . ' ' . $field->validation_message );
			}

			$new_resume_token = $this->save_draft_submission( $draft_entry, $resume_token );

            if ( ! $new_resume_token ) {
                throw new UserError( __( 'An error occurred while trying to update the draft entry.', 'wp-graphql-gravity-forms' ) );
			}

			// Verify that between mutations, the resume token remains the same.

			// $new_draft_entry = GFFormsModel::get_draft_submission_values( $new_resume_token );

			return [
				'resumeToken' => $new_resume_token,
				// 'fieldValues' => $new_draft_entry['submitted_values'],
			];
		};
	}

	/**
	 * @param array $form The form.
	 *
	 * @return GF_Field|null The field object or null if not found.
	 */
	private function get_field_by_id( array $form ) {
		$matching_fields = array_values( array_filter( $form['fields'], function( $field ) {
			return $field['id'] === $this->field_id;
		} ) );

		return $matching_fields[0] ?? null;
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::save_draft_submission() method.
	 *
	 * @param array  $draft_entry  The draft entry.
	 * @param string $resume_token The resume token.
	 *
	 * @return string The resume token, or empty string on failure.
	 */
	private function save_draft_submission( array $draft_entry, string $resume_token ) : string {
		$form           = GFFormsModel::get_form_meta( $draft_entry['form_id'] );
		$field_values   = $draft_entry['submission']['field_values'] ?? '';
		$page_number    = $draft_entry['submission']['page_number'] ?? 1; // TODO: Maybe get from request.
		$files          = $draft_entry['submission']['files'] ?? []; // TODO: Maybe get from request.
		$form_unique_id = $draft_entry['submission']['gform_unique_id'] ?? ''; // TODO: generate.
		$ip             = $draft_entry['submission']['partial_entry']['ip'] ?? '';
		$source_url     = $draft_entry['source_url'] ?? '';
		$entry          = $this->get_draft_entry_data( $draft_entry, $ip, $source_url );

		$new_resume_token = GFFormsModel::save_draft_submission(
			$form,
			$entry,
			$field_values,
			$page_number,
			$files,
			$form_unique_id,
			$ip,
			$source_url,
			$resume_token
		);

		return $new_resume_token ?: '';
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::create_lead() method.
	 *
	 * @param array  $draft_entry The draft entry.
	 * @param string $ip          IP address.
	 * @param string $source_url  Source URL.
	 *
	 * @return array Draft entry data.
	 */
	private function get_draft_entry_data( array $draft_entry, string $ip, string $source_url ) : array {
		return [
			'id'           => $draft_entry['submission']['partial_entry']['id'] ?? null,
			'post_id'      => $draft_entry['submission']['partial_entry']['post_id'] ?? null,
			'date_created' => $draft_entry['submission']['partial_entry']['date_created'] ?? null,
			'date_updated' => $draft_entry['submission']['partial_entry']['date_updated'] ?? null,
			'form_id'      => $draft_entry['form_id'],
			'ip'           => $ip,
			'source_url'   => $source_url,
			'user_agent'   => $draft_entry['submission']['partial_entry']['user_agent'] ?? '',
			'created_by'   => $draft_entry['submission']['partial_entry']['created_by'] ?? '',
			'currency'     => $draft_entry['submission']['partial_entry']['currency'] ?? '',
		];
	}

	/**
	 * @param array $submitted_values The submitted values
	 *
	 * @return array Submitted values, with new value added.
	 */
	public function add_submitted_value( array $submitted_values ) : array {
		$submitted_values[ $this->field_id ] = $this->value;

		return $submitted_values;
	}
}
