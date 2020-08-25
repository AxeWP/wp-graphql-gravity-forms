<?php

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;

/**
 * Delete a Gravity Forms entry.
 */
class DeleteEntry implements Hookable, Mutation {
    /**
     * Mutation name.
     */
    const NAME = 'deleteGravityFormsEntry';

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
            'entryId' => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'ID of the entry to delete', 'wp-graphql-gravity-forms' ),
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
				'description' => __( 'The ID of the entry that was deleted.', 'wp-graphql-gravity-forms' ),
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
			$entry_id         = (int) $input['entryId'];
			$does_entry_exist = GFAPI::entry_exists( $entry_id );

			if ( ! $does_entry_exist ) {
				throw new UserError( __( 'An invalid entry ID was provided.', 'wp-graphql-gravity-forms' ) );
			}

            $result = GFAPI::delete_entry( $entry_id );

			if ( is_wp_error( $result ) ) {
				throw new UserError( __( 'An error occurred while deleting the entry', 'wp-graphql-gravity-forms' ) );
			}

			return [ 'entryId' => $entry_id ];
		};
	}
}
