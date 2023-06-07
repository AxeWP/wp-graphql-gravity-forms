<?php
/**
 * Mutation - deleteGfEntry
 *
 * Registers mutation to delete a Gravity Forms entry.
 *
 * @package WPGraphQL\GF\Mutation
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Mutation;

use GFAPI;
use GFCommon;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Model\SubmittedEntry as ModelSubmittedEntry;
use WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry;
use WPGraphQL\GF\Utils\GFUtils;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - DeleteEntry
 */
class DeleteEntry extends AbstractMutation {
	/**
	 * Mutation Name
	 *
	 * @var string
	 */
	public static $name = 'deleteGfEntry';

	/**
	 * {@inheritDoc}
	 */
	public static function get_input_fields(): array {
		return [
			'id'          => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'ID of the entry to delete, either a global or database ID.', 'wp-graphql-gravity-forms' ),
			],
			'forceDelete' => [
				'type'        => 'Boolean',
				'description' => __( 'Whether the entry should be force deleted instead of being moved to the trash.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_output_fields(): array {
		return [
			'deletedId' => [
				'type'        => 'ID',
				'description' => __( 'The global ID of the draft entry that was deleted.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $payload ) {
					$deleted = (object) $payload['deletedEntry'];
					return ! empty( $deleted->id ) ? $deleted->id : null;
				},
			],
			'entry'     => [
				'type'        => SubmittedEntry::$type,
				'description' => __( 'The entry object before it was deleted.', 'wp-graphql-gravity-forms' ),
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

			$entry_id = Utils::get_entry_id_from_id( $input['id'] );

			if ( empty( $input['forceDelete'] ) ) {
				$result          = GFAPI::update_entry_property( $entry_id, 'status', 'trash' );
				$entry_to_return = GFUtils::get_entry( $entry_id );
			} else {
				$entry_to_return = GFUtils::get_entry( $entry_id );
				$result          = GFAPI::delete_entry( $entry_id );
			}

			if ( is_wp_error( $result ) ) {
				throw new UserError( __( 'An error occurred while deleting the entry. Error: .', 'wp-graphql-gravity-forms' ) . $result->get_error_message() );
			}

			return [
				'deletedEntry' => new ModelSubmittedEntry( $entry_to_return ),
			];
		};
	}
}
