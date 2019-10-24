<?php

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;
use WPGraphQLGravityForms\Types\FieldError\FieldError;

/**
 * Update a draft Gravity Forms entry with a new value.
 */
abstract class DraftEntryUpdater implements Hookable, Mutation {
	/**
	 * The ID of the field to be updated.
	 *
	 * @var int
	 */
	private $field_id = null;

	/**
	 * The value to update the field with.
     *
     * @var mixed
	 */
	private $value = null;

    public function register_hooks() {
		add_action( 'graphql_register_types',           [ $this, 'register_mutation' ] );
		add_filter( 'gform_submission_values_pre_save', [ $this, 'add_field_value' ] );
	}

	public function register_mutation() {
		register_graphql_mutation( static::NAME, [
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
	public function get_input_fields() : array {
		return [
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
			'fieldId' => [
				'type'        => 'Integer',
				'description' => __( 'Field ID.', 'wp-graphql-gravity-forms' ),
            ],
            'value' => $this->get_value_input_field(),
		];
    }

    /**
     * @return array The input field value.
     */
    abstract protected function get_value_input_field() : array;

	/**
	 * Defines the output field configuration.
	 *
	 * @return array
	 */
	public function get_output_fields() : array {
		return [
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
			'errors' => [
				'type'        => [ 'list_of' => FieldError::TYPE ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
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
			if ( empty( $input ) || ! is_array( $input ) || ! isset( $input['resumeToken'], $input['fieldId'], $input['value'] ) ) {
				throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
			}

            $this->field_id = absint( $input['fieldId'] );
			$this->value    = $this->sanitize_field_value( $input['value'] );
			$resume_token   = sanitize_text_field( $input['resumeToken'] );
			$draft_entry    = GFFormsModel::get_draft_submission_values( $resume_token );

			if ( ! is_array( $draft_entry ) || empty( $draft_entry['form_id'] ) ) {
				throw new UserError( __( 'A draft with this resume token could not be found.', 'wp-graphql-gravity-forms' ) );
			}

			$form = GFAPI::get_form( $draft_entry['form_id'] );

            if ( ! $form || ! $form['is_active'] || $form['is_trash'] ) {
                throw new UserError( __( 'The form associated with this entry is nonexistent or inactive.', 'wp-graphql-gravity-forms' ) );
            }

			$field = $this->get_field_by_id( $form );

			if ( ! $field ) {
                throw new UserError( __( 'The form associated with this entry does not contain a field with the field ID provided.', 'wp-graphql-gravity-forms' ) );
			}

			$field->validate( $this->value, $form );

			if ( $field->failed_validation ) {
				return [
					'resumeToken' => $resume_token,
					'errors' => [
						[
							'type'    => 'validation',
							'message' => $field->validation_message,
						],
					],
				];
            }

            $submission = json_decode( $draft_entry['submission'], true );

            if ( ! $submission ) {
                throw new UserError( __( 'The submission data for this draft entry could not be read.', 'wp-graphql-gravity-forms' ) );
            }

			$new_resume_token = $this->save_draft_submission( $form['id'], $submission, $resume_token );

            if ( ! $new_resume_token ) {
                throw new UserError( __( 'An error occurred while trying to update the draft entry.', 'wp-graphql-gravity-forms' ) );
            }

            // $new_draft_entry = GFFormsModel::get_draft_submission_values( $new_resume_token );

            return [
                'resumeToken' => $new_resume_token,
                // 'fieldValues' => $new_draft_entry['submitted_values'],
            ];
        };
    }

    /**
	 * Implement this method in child classes.
	 *
     * @param mixed The field value.
     *
     * @return mixed The sanitized field value.
     */
    // abstract protected function sanitize_field_value( $value );

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
     * @param int    $form_id      Form ID.
	 * @param array  $submission   Draft entry submission data.
	 * @param string $resume_token Resume token.
	 *
	 * @return string The resume token, or empty string on failure.
	 */
	private function save_draft_submission( int $form_id, array $submission, string $resume_token ) : string {
        $new_resume_token = GFFormsModel::save_draft_submission(
			GFFormsModel::get_form_meta( $form_id ),
            $this->add_field_value( $submission['partial_entry'] ),
            $submission['field_values'] ?? '',
            $submission['page_number'] ?? 1, // TODO: Maybe get from request
            $submission['files'] ?? [], // TODO: Maybe get from request
            $submission['gform_unique_id'] ?? $this->get_form_unique_id( $draft_entry['form_id'] ),
            $submission['partial_entry']['ip'] ?? '',
            $submission['partial_entry']['source_url'] ?? '',
            $resume_token
		);

		return $new_resume_token ?: '';
    }

	/**
	 * Mimics Gravity Forms' GFFormsModel::get_form_unique_id() method.
	 *
	 * @param int $form_id Form ID.
	 *
	 * @return string Unique ID.
	 */
	private function get_form_unique_id( int $form_id ) : string {		
		if ( ! isset( GFFormsModel::$unique_ids[ $form_id ] ) ) {
			GFFormsModel::$unique_ids[ $form_id ] = uniqid();
		}

		return GFFormsModel::$unique_ids[ $form_id ];
    }

	/**
	 * @param array $values Form values.
	 *
	 * @return array Form values, with new value added.
	 */
	public function add_field_value( array $values ) : array {
        if ( isset( $this->field_id, $this->value ) ) {
            $values[ $this->field_id ] = $this->value;
        }

		return $values;
	}
}
