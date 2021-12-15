<?php
/**
 * Connection - RootQueryForms
 * Registers all connections TO Gravity Forms Form.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.0.1
 */

namespace WPGraphQL\GF\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\WPObject\Form\Form;
use WPGraphQL\GF\Type\Enum\FormStatusEnum;
use WPGraphQL\GF\Type\Input\FormsConnectionOrderbyInput;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Class - FormConnections
 */
class FormConnections extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'gravityFormsForms';

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		// RootQuery to Form.
		register_graphql_connection(
			self::prepare_config(
				[
					'fromType'       => 'RootQuery',
					'toType'         => Form::$type,
					'fromFieldName'  => 'gravityFormsForms',
					'connectionArgs' => self::get_connection_args(),
					'resolve'        => static function ( $root, array $args, AppContext $context, ResolveInfo $info ) {
						return Factory::resolve_forms_connection( $root, $args, $context, $info );
					},
				]
			)
		);
	}

	/**
	 * {@inheritDoc}
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
			'orderby' => [
				'type'        => FormsConnectionOrderbyInput::$type,
				'description' => __( 'How to sort the entries.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
