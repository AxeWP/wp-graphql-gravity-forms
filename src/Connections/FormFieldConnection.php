<?php
/**
 * Connection - FormField
 *
 * Registers connections from GravityFormsForm.
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
use WPGraphQLGravityForms\DataManipulators\FieldsDataManipulator;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Types\Entry\Entry;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\GraphQLInterface\FormFieldInterface;

/**
 * Class - FormFieldConnection
 */
class FormFieldConnection implements Hookable, Connection {
	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
			add_action( 'init', [ $this, 'register_connection' ] );
	}

	/**
	 * Register connection from GravityFormsForm type to other types.
	 */
	public function register_connection() : void {
		// From GravityFormsForm to Field.
		register_graphql_connection(
			[
				'fromType'      => Form::TYPE,
				'toType'        => FormFieldInterface::TYPE,
				'fromFieldName' => 'formFields',
				'resolve'       => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
						$fields              = ( new FieldsDataManipulator() )->manipulate( $root['fields'] );
						$connection          = Relay::connectionFromArray( $fields, $args );
						$nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
						$connection['nodes'] = $nodes ?: null;
						return $connection;
				},
			]
		);
		/**
		 * Deprecated: `fields`.
		 *
		 * @since 0.4.0
		 */
		register_graphql_connection(
			[
				'deprecationReason' => __( 'Deprecated in favor of `formFields`.', 'wp-graphql-gravity-forms' ),
				'fromType'          => Form::TYPE,
				'toType'            => FormFieldInterface::TYPE,
				'fromFieldName'     => 'fields',
				'resolve'           => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
						$fields              = ( new FieldsDataManipulator() )->manipulate( $root['fields'] );
						$connection          = Relay::connectionFromArray( $fields, $args );
						$nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
						$connection['nodes'] = $nodes ?: null;
						return $connection;
				},
			]
		);

		// From GravityFormsForm to GravityFormsEntry.
		register_graphql_connection(
			[
				'fromType'      => Form::TYPE,
				'toType'        => Entry::TYPE,
				'fromFieldName' => 'entries',
				'resolve'       => function( array $root, array $args, AppContext $context, ResolveInfo $info ) : array {
					$form_entries = GFAPI::get_entries( $root['formId'] );
					if ( is_wp_error( $form_entries ) ) {
						throw new UserError( __( 'Error retrieving the form entries. Error: ', 'wp-graphql-gravity-forms' ) . $form_entries->get_error_message() );
					}

					$entry_data_manipulator = new EntryDataManipulator();
					$entries                = array_map( fn( array $entry ) => $entry_data_manipulator->manipulate( $entry ), $form_entries );
					$connection             = Relay::connectionFromArray( $entries, $args );

					return $connection;
				},
			]
		);
	}
}
