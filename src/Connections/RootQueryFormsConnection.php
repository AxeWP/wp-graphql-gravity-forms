<?php
/**
 * Connection - RootQueryForms
 * Registers connections from RootQuery.
 *
 * @package WPGraphQLGravityForms\Connections
 * @since 0.0.1
 */

namespace WPGraphQLGravityForms\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\Enum\FormStatusEnum;
use WPGraphQLGravityForms\Types\Input\FormsSortingInput;

/**
 * Class - RootQueryFormsConnection
 */
class RootQueryFormsConnection  extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'gravityFormsForms';

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
		return Form::$type;
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array
	 */
	public function get_connection_config_args() : array {
		return [
			'connectionArgs' => [
				'status' => [
					'type'        => FormStatusEnum::$type,
					'description' => __( 'Status of the forms to get.', 'wp-graphql-gravity-forms' ),
				],
				'sort'   => [
					'type'        => FormsSortingInput::$type,
					'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
				],
			],
			'resolve'        => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
				return ( new RootQueryFormsConnectionResolver() )->resolve( $root, $args, $context, $info );
			},
		];
	}
}
