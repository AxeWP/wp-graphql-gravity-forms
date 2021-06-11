<?php
/**
 * Connection - RootQueryEntries
 *
 * Registers connections from RootQuery.
 *
 * @package WPGraphQLGravityForms\Connections
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Deferred;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Enum\EntryStatusEnum;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersModeEnum;
use WPGraphQLGravityForms\Types\Input\EntriesDateFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesFieldFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesSortingInput;

/**
 * Class - RootQueryEntriesConnection
 */
class RootQueryEntriesConnection extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'gravityFormsEntries';

	/**
	 * GraphQL Connection from type.
	 *
	 * @return string
	 */
	public function get_connection_from_type() : string {
		return 'RootQuery';
	}

	/**
	 * GraphQL Connection to type.
	 *
	 * @return string
	 */
	public function get_connection_to_type() : string {
		return Entry::$type;
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array
	 */
	public function get_connection_config_args() : array {
		return [
			'connectionArgs' => [
				'formIds'          => [
					'type'        => [ 'list_of' => 'ID' ],
					'description' => __( 'Array of form IDs to limit the entries to. Exclude this argument to query all forms.', 'wp-graphql-gravity-forms' ),
				],
				'status'           => [
					'type'        => EntryStatusEnum::$type,
					'description' => __( 'Entry status. Default is "ACTIVE".', 'wp-graphql-gravity-forms' ),
				],
				'dateFilters'      => [
					'type'        => EntriesDateFiltersInput::$type,
					'description' => __( 'Date filters to apply.', 'wp-graphql-gravity-forms' ),
				],
				'fieldFilters'     => [
					'type'        => [ 'list_of' => EntriesFieldFiltersInput::$type ],
					'description' => __( 'Field-specific filters to apply.', 'wp-graphql-gravity-forms' ),
				],
				'fieldFiltersMode' => [
					'type'        => FieldFiltersModeEnum::$type,
					'description' => __( 'Whether to filter by ALL or ANY of the field filters. Default is ALL.', 'wp-graphql-gravity-forms' ),
				],
				'sort'             => [
					'type'        => EntriesSortingInput::$type,
					'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
				],
			],
			'resolve'        => function( $root, array $args, AppContext $context, ResolveInfo $info ) : Deferred {
				return ( new RootQueryEntriesConnectionResolver( $root, $args, $context, $info ) )->get_connection();
			},
		];
	}
}
