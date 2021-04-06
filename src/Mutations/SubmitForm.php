<?php
/**
 * Mutation - submitGravityFormsForm
 *
 * Submits a Gravity Forms form.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.4.0
 */

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;
use WPGraphQLGravityForms\DataManipulators\DraftEntryDataManipulator;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\FieldError\FieldError;
use WPGraphQLGravityForms\Types\Input\FieldValuesInput;
use WPGraphQLGravityForms\Utils\GFUtils;
use WPGraphQLGravityForms\Utils\Utils;

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
	 * EntryDataManipulator instance.
	 *
	 * @var EntryDataManipulator
	 */
	private $entry_data_manipulator;
	/**
	 * DraftEntryDataManipulator instance.
	 *
	 * @var DraftEntryDataManipulator
	 */
	private $draft_entry_data_manipulator;

	/**
	 * The Gravity Forms form object.
	 *
	 * @var array
	 */
	private $form;


	/**
	 * Constructor
	 *
	 * @param array $instances .
	 */
	public function __construct( array $instances ) {
		$this->entry_data_manipulator       = $instances['entry_data_manipulator'];
		$this->draft_entry_data_manipulator = $instances['draft_entry_data_manipulator'];
	}

	/**
	 * Defines the input field configuration.
	 *
	 * @return array
	 */
	public function get_input_fields() : array {
		return [
			'createdBy'   => [
				'type'        => 'Integer',
				'description' => __( 'Optional. ID of the user that submitted of the form if a logged in user submitted the form.', 'wp-graphql-gravity-forms' ),
			],
			'fieldValues' => [
				'type'        => [ 'list_of' => FieldValuesInput::TYPE ],
				'description' => __( 'The field ids and their values.', 'wp-graphql-gravity-forms' ),
			],
			'formId'      => [
				'type'        => [ 'non_null' => 'Integer' ],
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
				'type'        => 'Integer',
				'description' => __( 'Optional. Useful for multi-page forms to indicate which page of the form was just submitted.', 'wp-graphql-gravity-forms' ),
			],
			'targetPage'  => [
				'type'        => 'Integer',
				'description' => __( 'Optional. Useful for multi-page forms to indicate which page is to be loaded if the current page passes validation.', 'wp-graphql-gravity-forms' ),
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
			'entryId'     => [
				'type'        => 'Integer',
				'description' => __( 'The ID of the entry that was created. Null if the entry was only partially submitted or submitted as a draft.', 'wp-graphql-gravity-forms' ),
			],
			'entry'       => [
				'type'        => Entry::TYPE,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload ) {
					if ( ! empty( $payload['errors'] ) || ( ! $payload['entryId'] && ! $payload['resumeToken'] ) ) {
						return null;
					}

					if ( $payload['entryId'] ) {
						$entry = GFUtils::get_entry( $payload['entryId'] );

						return $this->entry_data_manipulator->manipulate( $entry );
					}

					if ( $payload['resumeToken'] ) {
						$submission = GFUtils::get_draft_submission( $payload['resumeToken'] );

						return $this->draft_entry_data_manipulator->manipulate( $submission['partial_entry'], $payload['resumeToken'] );
					}
				},
			],
			'errors'      => [
				'type'        => [ 'list_of' => FieldError::TYPE ],
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
	 * Defines the data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			// Check for required fields.
			$this->check_required_inputs( $input );

			$this->form = GFUtils::get_form( $input['formId'] );

			// Set default values.
			$target_page   = $input['targetPage'] ?? 0;
			$source_page   = $input['sourcePage'] ?? 0;
			$save_as_draft = $input['saveAsDraft'] ?? false;
			$ip            = empty( $this->form['personalData']['preventIP'] ) ? GFUtils::get_ip( $input['ip'] ?? '' ) : '';
			$created_by    = isset( $input['createdBy'] ) ? absint( $input['createdBy'] ) : null;
			$source_url    = esc_url_raw( Utils::truncate( $_SERVER['HTTP_REFERER'] ?? '', 250 ) );

			$field_values = $this->get_field_values( $input['fieldValues'] );

			add_filter( 'gform_field_validation', [ $this, 'disable_validation_for_unsupported_fields' ], 10, 4 );
			$submission = GFUtils::submit_form(
				$input['formId'],
				$this->get_input_values( $save_as_draft, $field_values ),
				$field_values,
				$target_page,
				$source_page,
			);
			remove_filter( 'gform_field_validation', [ $this, 'disable_validation_for_unsupported_fields' ] );

			if ( $submission['is_valid'] ) {
				$this->update_entry_properties( $submission, $ip, $source_url, $created_by );
			}

			return [
				'entryId'     => ! empty( $submission['entry_id'] ) ? absint( $submission['entry_id'] ) : null,
				'resumeToken' => $submission['resume_token'] ?? null,
				'resumeUrl'   => isset( $submission['resume_token'] ) ? GFUtils::get_resume_url( $source_url, $submission['resume_token'], $this->form ) : null,
				'errors'      => isset( $submission['validation_messages'] ) ? $this->get_submission_errors( $submission['validation_messages'] ) : null,
			];
		};
	}

	/**
	 * Gets the field values, properly formatted for Gravity Forms.
	 *
	 * @param array $field_values .
	 * @return array
	 */
	private function get_field_values( array $field_values ) : array {
		$field_values = $this->prepare_field_values( $field_values );

		return $this->rename_keys_for_field_values( $field_values );
	}

	/**
	 * Updates entry properties that cannot be set with GFAPI::submit_form().
	 *
	 * @param array   $submission The Gravity Forms submission result array.
	 * @param string  $ip .
	 * @param string  $source_url .
	 * @param integer $created_by .
	 * @throws UserError .
	 */
	private function update_entry_properties( array $submission, string $ip, string $source_url, int $created_by = null ) : void {
		if ( ! $submission['entry_id'] || empty( $submission['resume_token'] ) ) {
			return;
		}

		if ( $submission['resume_token'] ) {
			$draft_entry = GFUtils::get_draft_entry( $submission['resume_token'] );

			$ip         = $ip ?? $draft_entry['partial_entry']['ip'];
			$created_by = $created_by ?? $draft_entry['partial_entry']['created_by'];
			$is_updated = GFFormsModel::update_draft_submission( $submission['resume_token'], $this->form, $draft_entry['partial_entry']['date_created'], $ip, $source_url, $draft_entry['submission'] );
			if ( empty( $is_updated ) ) {
				throw new UserError( __( 'Unable to update the draft entry properties.', 'wp-graphql-gravity-forms' ) );
			}
			return;
		}

		if ( null !== $ip ) {
			$is_updated = GFAPI::update_entry_property( $submission['entry_id'], 'ip', $ip );
			if ( ! $is_updated ) {
				throw new UserError( __( 'Unable to update the entry IP address', 'wp-graphql-gravity-forms' ) );
			}
		}

		if ( null !== $created_by ) {
			$is_updated = GFAPI::update_entry_property( $submission['entry_id'], 'created_by', $created_by );
			if ( ! $is_updated ) {
				throw new UserError( __( 'Unable to update the entry createdBy id.', 'wp-graphql-gravity-forms' ) );
			}
		}

		$is_updated = GFAPI::update_entry_property( $submission['entry_id'], 'source_url', $source_url );
		if ( ! $is_updated ) {
			throw new UserError( __( 'Unable to update the entry source url', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Creates the $input_values array required by GFAPI::submit_form().
	 *
	 * @param boolean $is_draft .
	 * @param array   $field_values The field values. Required so submit_form() can generate the $_POST object.
	 * @return array
	 */
	private function get_input_values( bool $is_draft, array $field_values ) : array {
		return [
			'gform_save' => $is_draft,
		] + $field_values;
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
		if ( ! isset( $input['formId'] ) ) {
			throw new UserError( __( 'Mutation not processed. Form ID not provided.', 'wp-graphql-gravity-forms' ) );
		}
		if ( empty( $input['fieldValues'] ) ) {
			throw new UserError( __( 'Mutation not processed. Field values not provided.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
