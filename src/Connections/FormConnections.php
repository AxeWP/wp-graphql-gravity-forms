<?php
/**
 * Connection - RootQueryForms
 * Registers all connections TO Gravity Forms Form.
 *
 * @package WPGraphQL\GF\Connections
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Connections;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Types\Form\Form;
use WPGraphQL\GF\Types\Enum\FormStatusEnum;
use WPGraphQL\GF\Types\Input\FormsSortingInput;

/**
 * Class - FormConnections
 */
class FormConnections  extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'gravityFormsForms';

	/**
	 * {@inheritDoc}
	 */
	public function register_connections() : void {
		// RootQuery to Form.
		register_graphql_connection(
			$this->prepare_connection_config(
				[
					'fromType'       => 'RootQuery',
					'toType'         => Form::$type,
					'fromFieldName'  => 'gravityFormsForms',
					'connectionArgs' => self::get_connection_args(),
					'resolve'        => static function ( $root, array $args, AppContext $context, ResolveInfo $info ) {
						$resolver = new FormsConnectionResolver( $root, $args, $context, $info );

						return $resolver->get_connection();
					},
				]
			)
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array
	 */
	public static function get_connection_args() : array {
		return [
			'formIds' => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => __( 'Array of form IDs to return. Exclude this argument to query all forms.', 'wp-graphql-gravity-forms' ),
			],
			'status'  => [
				'type'        => FormStatusEnum::$type,
				'description' => __( 'Status of the forms to get.', 'wp-graphql-gravity-forms' ),
			],
			'sort'    => [
				'type'        => FormsSortingInput::$type,
				'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
