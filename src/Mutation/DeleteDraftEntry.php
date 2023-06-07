<?php
/**
 * Mutation - deleteGfDraftEntry
 *
 * Registers mutation to delete a Gravity Forms draft entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Mutation;

use GFCommon;
use GFFormsModel;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Model\DraftEntry as ModelDraftEntry;
use WPGraphQL\GF\Type\Enum\DraftEntryIdTypeEnum;
use WPGraphQL\GF\Type\WPObject\Entry\DraftEntry;
use WPGraphQL\GF\Utils\GFUtils;

/**
 * Class - DeleteDraftEntry
 */
class DeleteDraftEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'deleteGfDraftEntry';

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields(): array {
		return [
			'id'     => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'Either the global ID of the draft entry, or its resume token.', 'wp-graphql-gravity-forms' ),
			],
			'idType' => [
				'type'        => DraftEntryIdTypeEnum::$type,
				'description' => __( 'The ID type for the draft entry. Defaults to `ID` .', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields(): array {
		return [
			'deletedId'  => [
				'type'        => 'ID',
				'description' => __( 'The global ID of the draft entry that was deleted.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $payload ) {
					$deleted = (object) $payload['deletedEntry'];
					return ! empty( $deleted->id ) ? $deleted->id : null;
				},
			],
			'draftEntry' => [
				'type'        => DraftEntry::$type,
				'description' => __( 'The draft entry object before it was deleted.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $payload ) => $payload['deletedEntry'] ?? null,
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function mutate_and_get_payload(): callable {
		return static function ( $input, AppContext $context, ResolveInfo $info ): array {
			if ( ! GFCommon::current_user_can_any( 'gravityforms_delete_entries' ) ) {
				throw new UserError( __( 'Sorry, you are not allowed to delete entries.', 'wp-graphql-gravity-forms' ) );
			}

			$id_type      = isset( $input['idType'] ) ? $input['idType'] : 'global_id';
			$resume_token = self::get_resume_token_from_id( $input['id'], $id_type );

			$entry_before_delete = GFUtils::get_draft_entry( $resume_token );

			$result = GFFormsModel::delete_draft_submission( $resume_token );

			if ( ! $result ) {
				throw new UserError( __( 'An error occurred while trying to delete the draft entry.', 'wp-graphql-gravity-forms' ) );
			}

			return [
				'deletedEntry' => new ModelDraftEntry( $entry_before_delete, $resume_token ),
			];
		};
	}
}
