<?php
/**
 * Connection - RootQueryForms
 * Registers all connections TO Gravity Forms Form.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Factory;
use WPGraphQL\GF\Type\Enum\FormStatusEnum;
use WPGraphQL\GF\Type\Input\FormsConnectionOrderbyInput;
use WPGraphQL\GF\Type\WPObject\Form\Form;

/**
 * Class - FormsConnection
 */
class FormsConnection extends AbstractConnection {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name = 'gfForms';

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		// RootQuery to Form.
		register_graphql_connection(
			[
				'fromType'       => 'RootQuery',
				'toType'         => Form::$type,
				'fromFieldName'  => 'gfForms',
				'connectionArgs' => self::get_connection_args(),
				'resolve'        => static function ( $root, array $args, AppContext $context, ResolveInfo $info ) {
					return Factory::resolve_forms_connection( $root, $args, $context, $info );
				},
			]
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_connection_args(): array {
		return [
			'formIds' => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => __( 'Array of form database IDs to return. Exclude this argument to query all forms.', 'wp-graphql-gravity-forms' ),
			],
			// @todo make filtering more intuitive.
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
