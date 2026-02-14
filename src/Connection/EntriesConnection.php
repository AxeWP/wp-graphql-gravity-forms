<?php
/**
 * Connection - EntriesConnection
 *
 * Registers all connections TO Gravity Forms Entry.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Connection;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\Enum\EntryTypeEnum;
use WPGraphQL\GF\Type\Enum\FieldFiltersModeEnum;
use WPGraphQL\GF\Type\Input\EntriesConnectionOrderbyInput;
use WPGraphQL\GF\Type\Input\EntriesDateFiltersInput;
use WPGraphQL\GF\Type\Input\EntriesFieldFiltersInput;
use WPGraphQL\GF\Type\WPInterface\Entry;
use WPGraphQL\GF\Type\WPObject\Entry\SubmittedEntry;
use WPGraphQL\GF\Utils\Compat;

/**
 * Class - EntriesConnection
 */
class EntriesConnection extends AbstractConnection {
	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		// RootQuery to Entry.
		register_graphql_connection(
			Compat::resolve_graphql_config(
				[
					'fromType'       => 'RootQuery',
					'toType'         => Entry::$type,
					'fromFieldName'  => 'gfEntries',
					'connectionArgs' => self::get_filtered_connection_args(),
					'resolve'        => static function ( $root, array $args, AppContext $context, ResolveInfo $info ) {
						if ( isset( $args['entryType'] ) && EntryTypeEnum::SUBMITTED !== $args['entryType'] ) {
							throw new UserError( esc_html__( 'Only lists of `SUBMITTED` entries may currently be queried.', 'wp-graphql-gravity-forms' ) );
						}

						return Factory::resolve_entries_connection( $root, $args, $context, $info );
					},
				]
			)
		);

		// RootQuery to SubmittedEntry.
		register_graphql_connection(
			Compat::resolve_graphql_config(
				[
					'fromType'       => 'RootQuery',
					'toType'         => SubmittedEntry::$type,
					'fromFieldName'  => 'gfSubmittedEntries',
					'connectionArgs' => self::get_filtered_connection_args( [ 'formIds', 'dateFilters', 'fieldFilters', 'fieldFiltersMode', 'isRead', 'isStarred', 'orderby', 'status' ] ),
					'resolve'        => static function ( $root, array $args, AppContext $context, ResolveInfo $info ) {
						return Factory::resolve_entries_connection( $root, $args, $context, $info );
					},
				]
			)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connection_args(): array {
		return [
			'dateFilters'      => [
				'type'        => EntriesDateFiltersInput::$type,
				'description' => static fn () => __( 'Date filters to apply.', 'wp-graphql-gravity-forms' ),
			],
			'entryType'        => [
				'type'        => EntryTypeEnum::$type,
				'description' => static fn () => __( 'Entry status. Default is `SUBMITTED`. Currently no other types are supported.', 'wp-graphql-gravity-forms' ),
			],
			'fieldFilters'     => [
				'type'        => [ 'list_of' => EntriesFieldFiltersInput::$type ],
				'description' => static fn () => __( 'Field-specific filters to apply.', 'wp-graphql-gravity-forms' ),
			],
			'fieldFiltersMode' => [
				'type'        => FieldFiltersModeEnum::$type,
				'description' => static fn () => __( 'Whether to filter by ALL or ANY of the field filters. Default is ALL.', 'wp-graphql-gravity-forms' ),
			],
			'formIds'          => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => static fn () => __( 'Array of form IDs to limit the entries to. Exclude this argument to query all forms.', 'wp-graphql-gravity-forms' ),
			],
			'isRead'           => [
				'type'        => 'Boolean',
				'description' => static fn () => __( 'Whether to limit to read or unread entries. Default is to include both.', 'wp-graphql-gravity-forms' ),
			],
			'isStarred'        => [
				'type'        => 'Boolean',
				'description' => static fn () => __( 'Whether to limit to starred or unstarred entries. Default is to include both.', 'wp-graphql-gravity-forms' ),
			],
			'orderby'          => [
				'type'        => EntriesConnectionOrderbyInput::$type,
				'description' => static fn () => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
			],
			'status'           => [
				'type'        => EntryStatusEnum::$type,
				'description' => static fn () => __( 'Entry status. Default is "ACTIVE".', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
