<?php
/**
 * Abstract class for Mutations
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Mutation;

use GraphQL\Error\UserError;
use GraphQLRelay\Relay;
use WPGraphQL\GF\Data\Loader\DraftEntriesLoader;
use WPGraphQL\GF\Data\Loader\EntriesLoader;
use WPGraphQL\GF\Data\Loader\FormsLoader;
use WPGraphQL\GF\Interfaces\Mutation;
use WPGraphQL\GF\Interfaces\Registrable;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - AbstractMutation
 */
abstract class AbstractMutation implements Mutation, Registrable {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name;

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_mutation(
			static::$name,
			[
				'inputFields'         => static::get_input_fields(),
				'outputFields'        => static::get_output_fields(),
				'mutateAndGetPayload' => static::mutate_and_get_payload(),
			]
		);
	}

	/**
	 * Checks that necessary WPGraphQL are set.
	 *
	 * @param array $input .
	 * @throws UserError .
	 */
	protected static function check_required_inputs( ?array $input ) : void {
		if ( empty( $input ) || ! is_array( $input ) ) {
			throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Gets the resume token from an indeterminate GraphQL ID.
	 *
	 * @param int|string $id .
	 * @param string     $id_type .
	 * @throws UserError .
	 */
	protected static function get_resume_token_from_id( $id, string $id_type ) : string {
		if ( 'resume_token' === $id_type ) {
			$resume_token = $id;
		} else {
			$id_parts = Relay::fromGlobalId( $id );

			if ( empty( $id_parts['id'] ) || DraftEntriesLoader::$name !== $id_parts['type'] ) {
				throw new UserError( __( 'The ID passed is not a for a valid Gravity Forms draft entry.', 'wp-graphql-gravity-forms' ) );
			}

			$resume_token = $id_parts['id'];
		}

		return sanitize_text_field( $resume_token );
	}

	/**
	 * Gets the entry databaseId from an indeterminate GraphQL ID.
	 *
	 * @param int|string $id .
	 * @throws UserError .
	 */
	protected static function get_entry_id_from_id( $id ) : int {
		$id_parts = Relay::fromGlobalId( $id );

		if ( ! empty( $id_parts['id'] ) && ! empty( $id_parts['type'] ) ) {
			if ( EntriesLoader::$name !== $id_parts['type'] ) {
				throw new UserError( __( 'The ID passed is not a for a valid Gravity Forms entry.', 'wp-graphql-gravity-forms' ) );
			}

			$entry_id = $id_parts['id'];
		} else {
			$entry_id = $id;
		}

		return absint( $entry_id );
	}

	/**
	 * Gets the entry databaseId from an indeterminate GraphQL ID.
	 *
	 * @param int|string $id .
	 * @throws UserError .
	 */
	protected static function get_form_id_from_id( $id ) : int {
		$id_parts = Relay::fromGlobalId( $id );

		if ( ! empty( $id_parts['id'] ) && ! empty( $id_parts['type'] ) ) {
			if ( FormsLoader::$name !== $id_parts['type'] ) {
				throw new UserError( __( 'The ID passed is not a for a valid Gravity Forms form.', 'wp-graphql-gravity-forms' ) );
			}

			$form_id = $id_parts['id'];
		} else {
			$form_id = $id;
		}

		return absint( $form_id );
	}
}
