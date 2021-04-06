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
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Connection;
use WPGraphQLGravityForms\Types\Form\Form;
use WPGraphQLGravityForms\Types\Enum\FormStatusEnum;

/**
 * Class - RootQueryFormsConnection
 */
class RootQueryFormsConnection implements Hookable, Connection {
	/**
	 * The from field name.
	 */
	const FROM_FIELD = 'gravityFormsForms';


	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'init', [ $this, 'register_connection' ] );
	}

	/**
	 * Register connection from RootQuery type to GravityFormsForm type.
	 */
	public function register_connection() : void {
		register_graphql_connection(
			[
				'fromType'       => 'RootQuery',
				'toType'         => Form::TYPE,
				'fromFieldName'  => self::FROM_FIELD,
				'connectionArgs' => [
					'status' => [
						'type'        => FormStatusEnum::$type,
						'description' => __( 'Status of the forms to get.', 'wp-graphql-gravity-forms' ),
					],
				],
				'resolve'        => function( $root, array $args, AppContext $context, ResolveInfo $info ) {
					return ( new RootQueryFormsConnectionResolver() )->resolve( $root, $args, $context, $info );
				},
			]
		);
	}
}
