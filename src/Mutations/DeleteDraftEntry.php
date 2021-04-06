<?php
/**
 * Mutation - deleteGravityFormsDraftEntry
 *
 * Registers mutation to delete a Gravity Forms draft entry.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Mutations;

use GFFormsModel;
use GraphQL\Error\UserError;

/**
 * Class - DeleteDraftEntry
 */
class DeleteDraftEntry extends AbstractMutation {

	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'deleteGravityFormsDraftEntry';

	/**
	 * Defines the input field configuration.
	 *
	 * @return array
	 */
	public function get_input_fields() : array {
		return [
			'resumeToken' => [
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'Resume token of the draft to delete.', 'wp-graphql-gravity-forms' ),
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
				'description' => __( 'Resume token of the draft that was deleted.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Defines the mutation data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input ) : array {
			$this->check_required_inputs( $input );

			$resume_token = sanitize_text_field( $input['resumeToken'] );
			$result       = GFFormsModel::delete_draft_submission( $resume_token );

			if ( ! $result ) {
				throw new UserError( __( 'An error occurred while trying to delete the draft entry.', 'wp-graphql-gravity-forms' ) );
			}

			return [
				'resumeToken' => $resume_token,
			];
		};
	}

	/**
	 * Checks that necessary WPGraphQL are set.
	 *
	 * @since 0.4.0
	 *
	 * @param mixed $input .
	 * @throws UserError .
	 */
	protected function check_required_inputs( $input ) : void {
		parent::check_required_inputs( $input );
		if ( ! isset( $input['resumeToken'] ) ) {
				throw new UserError( __( 'Mutation not processed. The resumeToken must be set.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
