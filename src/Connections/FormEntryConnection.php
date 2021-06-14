<?php
/**
 * Connection - FormEntry
 *
 * Registers connection from GravityFormsForm to GravityFormsEntry.
 *
 * @package WPGraphQLGravityForms\Connections
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Connections;

use GFAPI;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\EntryDataManipulator;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Form\Form;

/**
 * Class - FormEntryConnection
 */
class FormEntryConnection extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'entries';

	/**
	 * GraphQL Connection from type.
	 *
	 * @return string
	 */
	public function get_connection_from_type() : string {
		return Form::$type;
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
			'resolve' => function( array $root, array $args, AppContext $context, ResolveInfo $info ) : array {
				$form_entries = GFAPI::get_entries( $root['formId'] );
				if ( is_wp_error( $form_entries ) ) {
					throw new UserError( __( 'Error retrieving the form entries. Error: ', 'wp-graphql-gravity-forms' ) . $form_entries->get_error_message() );
				}

				$entry_data_manipulator = new EntryDataManipulator();
				$entries                = array_map( fn( array $entry ) => $entry_data_manipulator->manipulate( $entry ), $form_entries );
				$connection             = Relay::connectionFromArray( $entries, $args );

				return $connection;
			},
		];
	}
}
