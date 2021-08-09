<?php
/**
 * Connection - EntryConnections
 *
 * Registers all connections TO Gravity Forms Entry.
 *
 * @package WPGraphQLGravityForms\Connections
 * @since 0.8.0
 */

namespace WPGraphQLGravityForms\Connections;

use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\Enum\EntryStatusEnum;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersModeEnum;
use WPGraphQLGravityForms\Types\Input\EntriesDateFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesFieldFiltersInput;
use WPGraphQLGravityForms\Types\Input\EntriesSortingInput;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

/**
 * Class - EntryConnections
 */
class EntryConnections extends AbstractConnection {

	/**
	 * {@inheritDoc}
	 */
	public function register_connections() : void {
		// RootQuery to Entry.
		register_graphql_connection(
			$this->prepare_connection_config(
				[
					'fromType'       => 'RootQuery',
					'toType'         => Entry::$type,
					'fromFieldName'  => 'gravityFormsEntries',
					'connectionArgs' => self::get_filtered_connection_args(),
					'resolve'        => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
						$resolver = new EntriesConnectionResolver( $root, $args, $context, $info );

						return $resolver->get_connection();
					},
				]
			)
		);

		// Form to Entry.
		register_graphql_connection(
			$this->prepare_connection_config(
				[
					'fromType'       => Form::$type,
					'toType'         => Entry::$type,
					'fromFieldName'  => 'entries',
					'connectionArgs' => self::get_filtered_connection_args( [ 'status', 'dateFilters', 'fieldFilters', 'fieldFiltersMode', 'sort' ] ),
					'resolve'        => static function( $root, array $args, AppContext $context, ResolveInfo $info ) {
						$resolver = new EntriesConnectionResolver( $root, $args, $context, $info );

						return $resolver->set_query_arg( 'form_ids', $root['formId'] )->get_connection();
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
