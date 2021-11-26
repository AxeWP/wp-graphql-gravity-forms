<?php
/**
 * Connection - EntryConnections
 *
 * Registers all connections TO Gravity Forms Entry.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.8.0
 */

namespace WPGraphQL\GF\Connection;

use WPGraphQL\GF\Type\WPObject\Entry\Entry;
use WPGraphQL\GF\Type\WPObject\Form\Form;
use WPGraphQL\GF\Type\Enum\EntryStatusEnum;
use WPGraphQL\GF\Type\Enum\FieldFiltersModeEnum;
use WPGraphQL\GF\Type\Input\EntriesDateFiltersInput;
use WPGraphQL\GF\Type\Input\EntriesFieldFiltersInput;
use WPGraphQL\GF\Type\Input\EntriesSortingInput;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - EntryConnections
 */
class EntryConnections extends AbstractConnection {
	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		// RootQuery to Entry.
		register_graphql_connection(
			self::prepare_config(
				[
					'fromType'       => 'RootQuery',
					'toType'         => Entry::$type,
					'fromFieldName'  => 'gravityFormsEntries',
					'connectionArgs' => self::get_filtered_connection_args(),
					'resolve'        => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
						return Factory::resolve_entries_connection( $root, $args, $context, $info );
					},
				]
			)
		);

		// Form to Entry.
		register_graphql_connection(
			self::prepare_config(
				[
					'fromType'       => Form::$type,
					'toType'         => Entry::$type,
					'fromFieldName'  => 'entries',
					'connectionArgs' => self::get_filtered_connection_args( [ 'status', 'dateFilters', 'fieldFilters', 'fieldFiltersMode', 'sort' ] ),
					'resolve'        => static function( $source, array $args, AppContext $context, ResolveInfo $info ) {
						$context->gfForm = $source;

						$args['where']['formIds'] = $source->formId ?? null;
						return Factory::resolve_entries_connection( $source, $args, $context, $info );
					},
				]
			),
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array
	 */
	public static function get_connection_args() : array {
		return [
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
		];
	}
}
