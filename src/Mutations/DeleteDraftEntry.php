<?php

namespace WPGraphQLGravityForms\Mutations;

use GFFormsModel;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;

/**
 * Delete a draft Gravity Forms entry.
 */
class DeleteDraftEntry implements Hookable, Mutation {
    /**
     * Mutation name.
     */
    const NAME = 'deleteGravityFormsDraftEntry';

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
			if ( empty( $input ) || ! is_array( $input ) || ! isset( $input['resumeToken'] ) ) {
				throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
            }

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
}
