<?php
/**
 * Mutation - UpdateGravityFormsDraftEntry
 *
 * Updates a Gravity Forms draft entry.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Mutations;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\DraftEntryDataManipulator;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\Types\Input\FieldValuesInput;
use WPGraphQLGravityForms\Utils\GFUtils;

/**
 * Class - UpdateDraftEntry
 */
class UpdateDraftEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'updateGravityFormsDraftEntry';

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
	private $submission = [];

	/**
	 * The Gravity Forms Form object.
	 *
	 * @var array
	 */
	private $form;

	/**
	 * Gravity Forms field validation errors.
	 *
	 * @var array
	 */
	protected $errors = [];

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
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues' => [
				'type'        => [ 'list_of' => FieldValuesInput::TYPE ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'ip'          => [
				'type'        => 'String',
				'description' => __( 'Client IP of user who submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'createdBy'   => [
				'type'        => 'Integer',
				'description' => __( 'ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
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
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft entry resume token.', 'wp-graphql-gravity-forms' ),
			],
			'entry'       => [
				'type'        => Entry::TYPE,
				'description' => __( 'The draft entry after the update mutation has been applied. If a validation error occurred, the draft entry will NOT have been updated with the invalid value provided.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload ) {
					if ( ! empty( $payload['errors'] ) || ! $payload['resumeToken'] ) {
						return null;
					}
					$submission = GFUtils::get_draft_submission( $payload['resumeToken'] );
					return $this->draft_entry_data_manipulator->manipulate( $submission['partial_entry'], $payload['resumeToken'] );
				},
			],
			'errors'      => [
				'type'        => [ 'list_of' => FieldError::TYPE ],
				'description' => __( 'Field errors.', 'wp-graphql-gravity-forms' ),
			],
			'resumeUrl'   => [
				'type'        => 'String',
				'description' => __( 'Draft resume URL. If the "Referer" header is not included in the request, this will be an empty string.', 'wp-graphql-gravity-forms' ),
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
			// Check for required fields.
			$this->check_required_inputs( $input );

			$resume_token = sanitize_text_field( $input['resumeToken'] );

			$this->submission = GFUtils::get_draft_submission( $resume_token );

			$this->form = GFUtils::get_form( $this->submission['partial_entry']['form_id'] );

			$values = $this->prepare_field_values( $input['fieldValues'] );
			if ( ! empty( $this->errors ) ) {
				return [ 'errors' => $this->errors ];
			}

			$this->submission['partial_entry'] = array_replace( $this->submission['partial_entry'], $values );
			$this->submission['field_values']  = array_replace( $this->submission['field_values'] ?? [], $this->rename_keys_for_field_values( $values ) );

			$ip                                      = empty( $this->form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['ip'] ?? '' ) : '';
			$this->submission['partial_entry']['ip'] = ! empty( $ip ) ? $ip : $this->submission['partial_entry']['ip'];

			$this->submission['partial_entry']['created_by'] = isset( $input['createdBy'] ) ? absint( $input['createdBy'] ) : $this->submission['partial_entry']['created_by'];

			$resume_token = GFUtils::save_draft_submission(
				$this->form,
				$this->submission['partial_entry'],
				$this->submission['field_values'],
				$this->submission['page_number'] ?? 1, // @TODO: Maybe get from request.
				$this->submission['files'] ?? [],
				$this->submission['gform_unique_id'] ?? null,
				$this->submission['partial_entry']['ip'],
				$this->submission['partial_entry']['source_url'] ?? '',
				$resume_token
			);

			return [
				'resumeToken' => $resume_token,
				'resumeUrl'   => GFUtils::get_resume_url( $this->submission['partial_entry']['source_url'], $resume_token, $this->form ),
			];
		};
	}

	/**
	 * Converts the provided field values into a format that Gravity Forms can understand.
	 *
	 * @param array $field_values .
	 * @return array
	 */
	private function prepare_field_values( array $field_values ) : array {
		$formatted_values = [];

		foreach ( $field_values as $values ) {
			$field = GFUtils::get_field_by_id( $this->form, $values['id'] );

			$this->validate_field_value_type( $field, $values );

			$value = $values['addressValues'] ?? $values['chainedSelectValues'] ?? $values['checkboxValues'] ?? $values['listValues'] ?? $values['nameValues'] ?? $values['values'] ?? $values['value'];

			$value = $this->prepare_field_value_by_type( $value, $field );

			$this->validate_field_value( $this->form, $field, $value );

			// Add field values to submitted values.
			$this->submission['submitted_values'][ $field->id ] = $value;

			// Add values to array based on field type.
			if ( in_array( $field->type, [ 'address', 'chainedselect', 'checkbox', 'consent', 'name' ], true ) ) {
				$formatted_values += $value;
			} else {
				$formatted_values[ $values['id'] ] = $value;
			}
		}

		return $formatted_values;
	}

	/**
	 * Ensures required input fields are set.
	 *
	 * @param mixed $input .
	 * @throws UserError .
	 */
	protected function check_required_inputs( $input = null ) : void {
		parent::check_required_inputs( $input );

		if ( ! isset( $input['resumeToken'] ) ) {
			throw new UserError( __( 'Mutation not processed. Resume token not provided.', 'wp-graphql-gravity-forms' ) );
		}

		if ( empty( $input['fieldValues'] ) ) {
			throw new UserError( __( 'Mutation not processed. Field values not provided.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
