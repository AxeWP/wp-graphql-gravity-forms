<?php
/**
 * Abstract class for updating a draft Gravity Forms entry with a new value
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Mutations;

use GF_Field;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\DataManipulators\DraftEntryDataManipulator;
use WPGraphQLGravityForms\Utils\GFUtils;
/**
 * Class - AbstractDraftEntryUpdater
 */
abstract class AbstractDraftEntryUpdater extends AbstractMutation {
	/**
	 * Gravity forms field type for the mutation.
	 *
	 * @var string
	 */
	protected static $gf_type;

	/**
	 * DraftEntryDataManipulator instance.
	 *
	 * @var DraftEntryDataManipulator
	 */
	private $draft_entry_data_manipulator;

	/**
	 * The draft submission.
	 *
	 * @var array
	 */
	protected $submission = [];

	/**
	 * The field whose value is being updated.
	 *
	 * @var GF_Field
	 */
	protected $field = null;

	/**
	 * The value to update the field with.
	 *
	 * @var mixed
	 */
	private $value = null;

	/**
	 * Constructor.
	 *
	 * @param DraftEntryDataManipulator $draft_entry_data_manipulator .
	 */
	public function __construct( DraftEntryDataManipulator $draft_entry_data_manipulator ) {
		$this->draft_entry_data_manipulator = $draft_entry_data_manipulator;
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
			'fieldId'     => [
				'type'        => 'Integer',
				'description' => __( 'Field ID.', 'wp-graphql-gravity-forms' ),
			],
			'value'       => $this->get_value_input_field(),
		];
	}

	/**
	 * Returns the input field value.
	 *
	 * @return array
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
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'Draft entry resume token.', 'wp-graphql-gravity-forms' ),
			],
			'entry'       => [
				'type'        => Entry::TYPE,
				'description' => __( 'The draft entry after the update mutation has been applied. If a validation error occurred, the draft entry will NOT have been updated with the invalid value provided.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload ) : array {
					$submission = GFUtils::get_draft_submission( $payload['resumeToken'] );
					return $this->draft_entry_data_manipulator->manipulate( $submission['partial_entry'], $payload['resumeToken'] );
				},
			],
			'errors'      => [
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
			$this->check_required_inputs( $input );

			$resume_token = sanitize_text_field( $input['resumeToken'] );

			$this->submission = GFUtils::get_draft_submission( $resume_token );

			$form = GFUtils::get_form( $this->submission['partial_entry']['form_id'] );

			$field_id    = absint( $input['fieldId'] );
			$this->field = GFUtils::get_field_by_id( $form, $field_id );
			if ( $this->field->type !== static::$gf_type ) {
				throw new UserError(
					sprintf(
						// translators: Gravity Forms field id, field type, and expected field type.
						__( 'Mutation not processed. Field id %1$s is of type %2$s, type %3$s expected.', 'wp-graphql-gravity-forms' ),
						$field_id,
						$this->field->type,
						static::$gf_type
					)
				);
			}

			if ( ! method_exists( $this, 'prepare_field_value' ) ) {
				throw new UserError( __( 'Mutation not processed. Field values could not be prepared', 'wp-graphql-gravity-forms' ) );
			}
			$this->value = $this->prepare_field_value( $input['value'] );
			// Validate the field.
			$this->field->validate( $this->value, $form );
			if ( $this->field->failed_validation ) {
				return [
					'resumeToken' => $resume_token,
					'errors'      => [
						[ 'message' => $this->field->validation_message ],
					],
				];
			}

			// Add the values to the `submitted_values` array in the draft submission.
			add_filter( 'gform_submission_values_pre_save', [ $this, 'add_field_value_to_submitted_values' ] );

			$value_array = $this->flatten_field_values( $this->field, $this->value );

			// Add value to partial entry.
			$this->submission['partial_entry'] = array_replace( $this->submission['partial_entry'], $value_array );

			// Add value to field values.
			$this->submission['field_values'] = array_replace( $this->submission['field_values'] ?? [], $this->rename_keys_for_field_values( $value_array ) );

			$resume_token = GFUtils::save_draft_submission(
				$form,
				$this->submission['partial_entry'],
				$this->submission['field_values'],
				$this->submission['page_number'] ?? 1, // @TODO: Maybe get from request.
				$this->submission['files'] ?? [],
				$this->submission['gform_unique_id'] ?? null,
				$this->submission['partial_entry']['ip'] ?? null,
				$this->submission['partial_entry']['source_url'] ?? '',
				$resume_token
			);

			remove_filter( 'gform_submission_values_pre_save', [ $this, 'add_field_value_to_submitted_values' ] );

			return [ 'resumeToken' => $resume_token ];
		};
	}

	/**
	 * Checks that necessary WPGraphQL are set.
	 *
	 * @param mixed $input .
	 * @throws UserError .
	 */
	protected function check_required_inputs( $input ) : void {
		parent::check_required_inputs( $input );
		if ( ! isset( $input['resumeToken'] ) ) {
				throw new UserError( __( 'Mutation not processed. The resumeToken must be set.', 'wp-graphql-gravity-forms' ) );
		}

		if ( ! isset( $input['fieldId'] ) ) {
			throw new UserError( __( 'Mutation not processed. The fieldId must be set.', 'wp-graphql-gravity-forms' ) );
		}

		if ( ! isset( $input['value'] ) ) {
			throw new UserError( __( 'Mutation not processed. The value must be set.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Implement this method in child classes.
	 * abstract protected function prepare_field_value( $value );
	 *
	 * @param mixed $value The field value.
	 *
	 * @return mixed The prepared and sanitized field value.
	 */


	/**
	 * Returns submitted values, with new value added.
	 *
	 * @return array
	 */
	public function add_field_value_to_submitted_values() : array {
		if ( isset( $this->field, $this->value ) ) {
			$this->submission['submitted_values'][ $this->field->id ] = $this->value;
		}
		return $this->submission['submitted_values'];
	}
}
