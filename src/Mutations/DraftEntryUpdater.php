<?php

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GF_Field;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\DataManipulators\DraftEntryDataManipulator;

/**
 * Update a draft Gravity Forms entry with a new value.
 */
abstract class DraftEntryUpdater implements Hookable, Mutation {
    /**
     * DraftEntryDataManipulator instance.
     */
    private $draft_entry_data_manipulator;

    public function __construct( DraftEntryDataManipulator $draft_entry_data_manipulator ) {
        $this->draft_entry_data_manipulator = $draft_entry_data_manipulator;
	}

	/**
	 * The draft submission.
	 *
	 * @var array
	 */
	protected $submission = [];

	/**
	 * The field whose value is being updated.
	 *
	 * @var int
	 */
	protected $field = null;

	/**
	 * The value to update the field with.
     *
     * @var mixed
	 */
	private $value = null;

    public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_mutation' ] );
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
				'description' => __( 'Draft entry resume token.', 'wp-graphql-gravity-forms' ),
			],
			'entry' => [
				'type'        => Entry::TYPE,
				'description' => __( 'The draft entry after the update mutation has been applied. If a validation error occurred, the draft entry will NOT have been updated with the invalid value provided.', 'wp-graphql-gravity-forms' ),
				'resolve' => function( array $payload ) : array {
					$submission = $this->get_draft_submission( $payload['resumeToken'] );
					return $this->draft_entry_data_manipulator->manipulate( $submission['partial_entry'], $payload['resumeToken'] );
				}
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

			$resume_token     = sanitize_text_field( $input['resumeToken'] );
			$this->submission = $this->get_draft_submission( $resume_token );
			$form             = $this->get_draft_form();
			$field_id         = absint( $input['fieldId'] );
			$this->field      = $this->get_field_by_id( $form, $field_id );
			$this->value      = $this->prepare_field_value( $input['value'] );

			$this->field->validate( $this->value, $form );

			if ( $this->field->failed_validation ) {
				return [
					'resumeToken' => $resume_token,
					'errors'      => [
						[ 'message' => $this->field->validation_message ],
					],
				];
			}

			add_filter( 'gform_submission_values_pre_save', [ $this, 'add_field_value_to_submitted_values' ] );

			$resume_token = $this->save_draft_submission( $form['id'], $resume_token );

			remove_filter( 'gform_submission_values_pre_save', [ $this, 'add_field_value_to_submitted_values' ] );

            return [ 'resumeToken' => $resume_token ];
        };
	}

	/**
	 * @param string $resume_token Draft entry resume token.
	 *
	 * @return array $submission Draft entry submission data.
	 */
	private function get_draft_submission( string $resume_token ) : array {
		$draft_entry = GFFormsModel::get_draft_submission_values( $resume_token );

		if ( ! is_array( $draft_entry ) || empty( $draft_entry ) ) {
			throw new UserError( __( 'A draft with this resume token could not be found.', 'wp-graphql-gravity-forms' ) );
		}

		$submission = json_decode( $draft_entry['submission'], true );

		if ( ! $submission ) {
			throw new UserError( __( 'The submission data for this draft entry could not be read.', 'wp-graphql-gravity-forms' ) );
		}

		return $submission;
	}

	/**
	 * @return array Gravity Form associated with the draft entry.
	 */
	private function get_draft_form() : array {
		$form = GFAPI::get_form( $this->submission['partial_entry']['form_id'] );

		if ( ! $form || ! $form['is_active'] || $form['is_trash'] ) {
			throw new UserError( __( 'The form associated with this entry is nonexistent or inactive.', 'wp-graphql-gravity-forms' ) );
		}

		return $form;
	}

    /**
	 * Implement this method in child classes.
	 *
     * @param mixed $value The field value.
     *
     * @return mixed The prepared and sanitized field value.
     */
    // abstract protected function prepare_field_value( $value );

	/**
	 * @param array $form     The form.
	 * @param int   $field_id Field ID.
	 *
	 * @return GF_Field The field object.
	 */
	private function get_field_by_id( array $form, int $field_id ) : GF_Field {
		$matching_fields = array_values( array_filter( $form['fields'], function( GF_Field $field ) use ( $field_id ) : bool {
			return $field['id'] === $field_id;
		} ) );

		if ( ! $matching_fields ) {
			throw new UserError( __( 'The form associated with this entry does not contain a field with the field ID provided.', 'wp-graphql-gravity-forms' ) );
		}

		return $matching_fields[0];
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::save_draft_submission() method.
	 *
     * @param int    $form_id      Form ID.
	 * @param string $resume_token Resume token.
	 *
	 * @return string The resume token, or empty string on failure.
	 */
	private function save_draft_submission( int $form_id, string $resume_token ) : string {
        $new_resume_token = GFFormsModel::save_draft_submission(
			GFFormsModel::get_form_meta( $form_id ),
            $this->add_field_value_to_partial_entry( $this->submission['partial_entry'] ),
            $this->submission['field_values'] ?? '',
            $this->submission['page_number'] ?? 1, // TODO: Maybe get from request
            $this->submission['files'] ?? [], // TODO: Maybe get from request
            $this->submission['gform_unique_id'] ?? $this->get_form_unique_id( $draft_entry['form_id'] ),
            $this->submission['partial_entry']['ip'] ?? '',
            $this->submission['partial_entry']['source_url'] ?? '',
            $resume_token
		);

		if ( ! $new_resume_token ) {
			throw new UserError( __( 'An error occurred while trying to update the draft entry.', 'wp-graphql-gravity-forms' ) );
		}

		return $new_resume_token;
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
	 * @param array $partial_entry Partial form entry.
	 *
	 * @return array Partial entry, with new value added.
	 */
	public function add_field_value_to_partial_entry( array $partial_entry ) : array {
		if ( ! isset( $this->field, $this->value ) ) {
            return $partial_entry;
		}

		// For an array of sub-values, add each to the partial entry individually.
		if ( is_array( $this->value ) ) {
			foreach ( $this->value as $key => $single_value ) {
				$partial_entry[ $key ] = $single_value;
			}

			return $partial_entry;
		}

		// Else, add the single value to the partial entry.
		$partial_entry[ $this->field->id ] = $this->value;

		return $partial_entry;
	}

	/**
	 * @param array $submitted_values Submitted form values.
	 *
	 * @return array Submitted values, with new value added.
	 */
	public function add_field_value_to_submitted_values() : array {
        if ( isset( $this->field, $this->value ) ) {
			$this->submission['submitted_values'][ $this->field->id ] = $this->value;
        }

		return $this->submission['submitted_values'];
	}
}
