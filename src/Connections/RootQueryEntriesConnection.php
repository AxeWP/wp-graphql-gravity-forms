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
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Enum\EntryStatusEnum;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersModeEnum;
use WPGraphQLGravityForms\Types\Input\EntriesDateFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesFieldFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesSortingInput;

/**
 * Class - RootQueryEntriesConnection
 */
class RootQueryEntriesConnection implements Hookable, Connection {
	/**
	 * The from field name.
	 */
	const FROM_FIELD = 'gravityFormsEntries';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'init', [ $this, 'register_connection' ] );
	}

	/**
	 * Register connection from RootQuery type to GravityFormsEntry type.
	 */
	public function register_connection() : void {
		register_graphql_connection(
			[
				'fromType'       => 'RootQuery',
				'toType'         => Entry::TYPE,
				'fromFieldName'  => self::FROM_FIELD,
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
			]
		);
	}
}
