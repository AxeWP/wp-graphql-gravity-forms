<?php
/**
 * Mutation - deleteGravityFormsDraftEntry
 *
 * Registers mutation to delete a Gravity Forms draft entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Mutation;

use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

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
	 * {@inheritDoc}
	 */
	public static function get_input_fields() : array {
		return [
			'resumeToken' => [
				'type'        => [ 'non_null' => 'String' ],
				'description' => __( 'Resume token of the draft to delete.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields() : array {
		return [
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Resume token of the draft that was deleted.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			static::check_required_inputs( $input );

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
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	protected static function check_required_inputs( $input ) : void {
		parent::check_required_inputs( $input );
		if ( ! isset( $input['resumeToken'] ) ) {
				throw new UserError( __( 'Mutation not processed. The resumeToken must be set.', 'wp-graphql-gravity-forms' ) );
		}
	}
}
