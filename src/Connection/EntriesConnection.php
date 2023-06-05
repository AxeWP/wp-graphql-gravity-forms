<?php
/**
 * Connection - EntriesConnection
 *
 * Registers all connections TO Gravity Forms Entry.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.10.0
 */

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
			[
				'fromType'       => 'RootQuery',
				'toType'         => Entry::$type,
				'fromFieldName'  => 'gfEntries',
				'connectionArgs' => self::get_filtered_connection_args(),
				'resolve'        => static function ( $root, array $args, AppContext $context, ResolveInfo $info ) {
					if ( isset( $args['entryType'] ) && EntryTypeEnum::SUBMITTED !== $args['entryType'] ) {
						throw new UserError( __( 'Only lists of `SUBMITTED` entries may currently be queried.', 'wp-graphql-gravity-forms' ) );
					}

					return Factory::resolve_entries_connection( $root, $args, $context, $info );
				},
			]
		);

		// RootQuery to SubmittedEntry.
		register_graphql_connection(
			[
				'fromType'       => 'RootQuery',
				'toType'         => SubmittedEntry::$type,
				'fromFieldName'  => 'gfSubmittedEntries',
				'connectionArgs' => self::get_filtered_connection_args( [ 'formIds', 'dateFilters', 'fieldFilters', 'fieldFiltersMode', 'orderby', 'status' ] ),
				'resolve'        => static function ( $root, array $args, AppContext $context, ResolveInfo $info ) {
					return Factory::resolve_entries_connection( $root, $args, $context, $info );
				},
			]
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connection_args(): array {
		return [
			'dateFilters'      => [
				'type'        => EntriesDateFiltersInput::$type,
				'description' => __( 'Date filters to apply.', 'wp-graphql-gravity-forms' ),
			],
			'entryType'        => [
				'type'        => EntryTypeEnum::$type,
				'description' => __( 'Entry status. Default is `SUBMITTED`. Currently no other types are supported.', 'wp-graphql-gravity-forms' ),
			],
			'formIds'          => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => __( 'Array of form IDs to limit the entries to. Exclude this argument to query all forms.', 'wp-graphql-gravity-forms' ),
			],
			'fieldFilters'     => [
				'type'        => [ 'list_of' => EntriesFieldFiltersInput::$type ],
				'description' => __( 'Field-specific filters to apply.', 'wp-graphql-gravity-forms' ),
			],
			'fieldFiltersMode' => [
				'type'        => FieldFiltersModeEnum::$type,
				'description' => __( 'Whether to filter by ALL or ANY of the field filters. Default is ALL.', 'wp-graphql-gravity-forms' ),
			],
			'orderby'          => [
				'type'        => EntriesConnectionOrderbyInput::$type,
				'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
			],
			'status'           => [
				'type'        => EntryStatusEnum::$type,
				'description' => __( 'Entry status. Default is "ACTIVE".', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
