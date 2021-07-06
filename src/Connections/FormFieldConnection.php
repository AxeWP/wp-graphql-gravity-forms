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

use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\DataManipulators\FieldsDataManipulator;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\GraphQLInterface\FormFieldInterface;

/**
 * Class - FormFieldConnection
 */
class FormFieldConnection extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'formFields';

	/**
	 * GraphQL Connection from type.
	 */
	public function get_connection_from_type() : string {
		return Form::$type;
	}

	/**
	 * GraphQL Connection to type.
	 */
	public function get_connection_to_type() : string {
		return FormFieldInterface::$type;
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 */
	public function get_connection_config_args() : array {
		return [
			'resolve' => function( array $root, array $args, AppContext $context, ResolveInfo $info ) {
				$fields              = ( new FieldsDataManipulator() )->manipulate( $root['fields'] );
				$connection          = Relay::connectionFromArray( $fields, $args );
				$nodes               = array_map( fn( $edge ) => $edge['node'] ?? null, $connection['edges'] );
				$connection['nodes'] = $nodes ?: null;
				return $connection;
			},
		];
	}
}
