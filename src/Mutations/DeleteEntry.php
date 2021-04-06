<?php
/**
 * Mutation - deleteGravityFormsEntry
 *
 * Registers mutation to delete a Gravity Forms entry.
 *
 * @package WPGraphQLGravityForms\Mutation
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Mutations;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

/**
 * Class - DeleteEntry
 */
class DeleteEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'deleteGravityFormsEntry';

	/**
	 * Defines the input field configuration.
	 *
	 * @return array
	 */
	public function get_input_fields() : array {
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
			$this->check_required_inputs( $input );

			$entry_id         = (int) $input['entryId'];
			$does_entry_exist = GFAPI::entry_exists( $entry_id );

			if ( ! $does_entry_exist ) {
				throw new UserError( __( 'An invalid entry ID was provided.', 'wp-graphql-gravity-forms' ) );
			}

			$result = GFAPI::delete_entry( $entry_id );

			if ( is_wp_error( $result ) ) {
				throw new UserError( __( 'An error occurred while deleting the entry. Error: ', 'wp-graphql-gravity-forms' ) . $result->get_error_message() );
			}

			return [ 'entryId' => $entry_id ];
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
		if ( ! isset( $input['entryId'] ) ) {
				throw new UserError( __( 'Mutation not processed. The entryId must be set.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
