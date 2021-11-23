<?php
/**
 * Abstract class for Mutations
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.4.0
 */

namespace WPGraphQL\GF\Mutation;

use GraphQL\Error\UserError;
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


}
