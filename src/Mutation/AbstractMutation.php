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
use WPGraphQL\GF\Interfaces\Mutation;
use WPGraphQL\GF\Type\AbstractType;

/**
 * Class - AbstractMutation
 */
abstract class AbstractMutation extends AbstractType implements Mutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name;

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		$config = static::get_type_config();
		register_graphql_mutation( static::$name, $config );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_type_config(): array {
		return [
			'inputFields'         => static::get_input_fields(),
			'outputFields'        => static::get_output_fields(),
			'mutateAndGetPayload' => static::mutate_and_get_payload(),
		];
	}

	/**
	 * Checks that necessary WPGraphQL are set.
	 *
	 * @param array $input .
	 * @throws \GraphQL\Error\UserError .
	 */
	protected static function check_required_inputs( ?array $input ): void {
		if ( empty( $input ) || ! is_array( $input ) ) {
			throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
		}
	}

	/**
	 * Gets the resume token from an indeterminate GraphQL ID.
	 *
	 * @param int|string $id .
	 * @param string     $id_type .
	 * @throws \GraphQL\Error\UserError .
	 */
	protected static function get_resume_token_from_id( $id, string $id_type ): string {
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
}
