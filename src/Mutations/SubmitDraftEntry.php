<?php
/**
 * Mutation - submitGravityFormsDraftEntry
 *
 * Registers mutation to submit a Gravity Forms draft entry so that it becomes a permanent entry.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 * @since 0.3.0 Support post creation.
 */

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GFCOMMON;
use GF_Field;
use GFFormDisplay;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;

/**
 * Class - SubmitDraftEntry
 */
class SubmitDraftEntry implements Hookable, Mutation {
	/**
	 * Mutation name.
	 */
	const NAME = 'submitGravityFormsDraftEntry';

	/**
	 * EntryDataManipulator instance.
	 *
	 * @var EntryDataManipulator
	 */
	private $entry_data_manipulator;

	/**
	 * Constructor
	 *
	 * @param EntryDataManipulator $entry_data_manipulator .
	 */
	public function __construct( EntryDataManipulator $entry_data_manipulator ) {
		$this->entry_data_manipulator = $entry_data_manipulator;
	}

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_mutation' ] );
		add_action( 'graphql_before_resolve_field', [ $this, 'ensure_required_fields_are_set' ], 10, 7 );
	}

	/**
	 * Registers mutation.
	 */
	public function register_mutation() {
		register_graphql_mutation(
			self::NAME,
			[
				'inputFields'         => $this->get_input_fields(),
				'outputFields'        => $this->get_output_fields(),
				'mutateAndGetPayload' => $this->mutate_and_get_payload(),
			]
		);
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
			'entry'   => [
				'type'        => Entry::TYPE,
				'description' => __( 'The entry that was created.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $payload ) : array {
					return $this->entry_data_manipulator->manipulate( GFAPI::get_entry( $payload['entryId'] ) );
				},
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
			$form_id      = $draft_entry['form_id'];

			// Sets last page.
			$this->set_form_page_to_last( $form_id );

			$this->validate_form_id( $form_id );

			$entry_id = $this->create_entry( $draft_entry );

			$this->create_post( $form_id, $entry_id );

			$this->send_notifications( $form_id, $entry_id );

			GFFormsModel::delete_draft_submission( $resume_token );
			GFFormsModel::purge_expired_draft_submissions();

			return [ 'entryId' => $entry_id ];
		};
	}

	/**
	 * Returns draft entry from a given resume token.
	 *
	 * @param string $resume_token .
	 * @return array
	 *
	 * @throws UserError .
	 */
	private function get_draft_entry( string $resume_token ) : array {
		$draft_entry = GFFormsModel::get_draft_submission_values( $resume_token );

		if ( ! is_array( $draft_entry ) || empty( $draft_entry['form_id'] ) ) {
			throw new UserError( __( 'A draft with this resume token could not be found.', 'wp-graphql-gravity-forms' ) );
		}

		return $draft_entry;
	}

	/**
	 * Checks if form id exists.
	 *
	 * @param integer $form_id .
	 * @throws UserError .
	 */
	private function validate_form_id( int $form_id ) {
		$form_info = GFFormsModel::get_form( $form_id, true );

		if ( ! $form_info || ! $form_info->is_active || $form_info->is_trash ) {
			throw new UserError( __( 'The form associated with this entry is nonexistent or inactive.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Creates Gravity Forms entry from draft entry.
	 *
	 * @param array $draft_entry .
	 * @return integer
	 * @throws UserError .
	 */
	private function create_entry( array $draft_entry ) : int {
		$submission = $this->get_draft_submission( $draft_entry );
		$entry_id   = GFAPI::add_entry( $submission['partial_entry'] );

		if ( is_wp_error( $entry_id ) ) {
			throw new UserError( __( 'An error occurred while trying to submit the draft entry.', 'wp-graphql-gravity-forms' ) . ' ' . $entry_id->get_error_message() );
		}

		return $entry_id;
	}

	/**
	 * Create WordPress post if the form has any post fields.
	 *
	 * @param integer $form_id .
	 * @param integer $entry_id .
	 * @throws UserError .
	 */
	private function create_post( int $form_id, int $entry_id ) {
		$form  = GFAPI::get_form( $form_id );
		$entry = GFAPI::get_entry( $entry_id );

		if ( ! $form || ! $entry ) {
			throw new UserError( __( 'An error occured while trying to create a post from the form submission. Form or entry not found', 'wp-graphql-gravity-forms' ) );
		}

		GFCOMMON::create_post( $form, $entry );
	}

	/**
	 * Triggers Gravity Forms Notificiations associated with the entry.
	 *
	 * @param integer $form_id .
	 * @param integer $entry_id .
	 * @throws UserError .
	 */
	private function send_notifications( int $form_id, int $entry_id ) {
		$form  = GFAPI::get_form( $form_id );
		$entry = GFAPI::get_entry( $entry_id );

		if ( ! $form || ! $entry ) {
			throw new UserError( __( 'An error occurred while trying to send notifications, form or entry not found.', 'wp-graphql-gravity-forms' ) );
		}

		GFAPI::send_notifications( $form, $entry );
	}

	/**
	 * Gets draft submission data.
	 *
	 * @param array $draft_entry .
	 * @return array
	 * @throws UserError .
	 */
	private function get_draft_submission( array $draft_entry ) : array {
		$submission = json_decode( $draft_entry['submission'], true );

		if ( ! $submission ) {
			throw new UserError( __( 'The submission data for this draft entry could not be read.', 'wp-graphql-gravity-forms' ) );
		}

		return $submission;
	}

	/**
	 * Fire an action BEFORE the field resolves
	 *
	 * @param mixed       $source         Source passed down the Resolve Tree.
	 * @param array       $args           Args for the field.
	 * @param AppContext  $context        AppContext passed down the ResolveTree.
	 * @param ResolveInfo $info           ResolveInfo passed down the ResolveTree.
	 * @param mixed       $field_resolver Field resolver.
	 * @param string      $type_name      Name of the type the fields belong to.
	 * @param string      $field_key      Name of the field.
	 *
	 * @throws UserError .
	 */
	public function ensure_required_fields_are_set( $source, array $args, AppContext $context, ResolveInfo $info, $field_resolver, string $type_name, string $field_key ) : void {
		// Make sure this is the submitGravityFormsDraftEntry field on the RootMutation.
		if ( 'RootMutation' !== $type_name || self::NAME !== $field_key ) {
			return;
		}
		$draft_entry      = $this->get_draft_entry( $args['input']['resumeToken'] );
		$submission       = $this->get_draft_submission( $draft_entry );
		$submitted_values = $submission['submitted_values'];
		$form             = GFAPI::get_form( $submission['partial_entry']['form_id'] );
		$fields           = $form['fields'];

		foreach ( $fields as $field ) {
			if ( 'captcha' === $field['type'] ) {
				$field_id          = absint( $field['id'] );
				$field_to_validate = $this->get_field_by_id( $form, $field_id );
				$field_value       = $submitted_values[ $field_id ];

				$field_to_validate->validate( $field_value, $form );

				if ( $field->isRequired && empty( $submitted_values[ $field_id ] ) ) {
					$field->failed_validation = true;
				}

				if ( $field_to_validate->failed_validation ) {
					throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
				}
			}
		}
	}

	/**
	 * Returns the Gravity Forms field object for a given field id.
	 *
	 * @param array $form     The form.
	 * @param int   $field_id Field ID.
	 *
	 * @return GF_Field
	 * @throws UserError .
	 */
	private function get_field_by_id( array $form, int $field_id ) : GF_Field {
		$matching_fields = array_values(
			array_filter(
				$form['fields'],
				function( GF_Field $field ) use ( $field_id ) : bool {
					return $field['id'] === $field_id;
				}
			)
		);

		if ( ! $matching_fields ) {
			throw new UserError( __( 'The form associated with this entry does not contain a field with the field ID provided.', 'wp-graphql-gravity-forms' ) );
		}

		return $matching_fields[0];
	}

	/**
	 * Sets form page to last page so post creation can work.
	 *
	 * @param integer $form_id .
	 */
	private function set_form_page_to_last( int $form_id ) {
		require_once GFCOMMON::get_base_path() . '/form_display.php';

		$form = GFAPI::get_form( $form_id );

		GFFormDisplay::set_current_page( $form_id, GFFormDisplay::get_max_page_number( $form ) );
	}
}
