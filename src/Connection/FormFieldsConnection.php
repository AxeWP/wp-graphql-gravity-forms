<?php
/**
 * Connection - EntriesConnection
 *
 * Registers all connections TO Gravity Forms Field.
 *
 * @package WPGraphQL\GF\Connection
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Connection;

use WPGraphQL\GF\Type\Enum\FormFieldTypeEnum;

/**
 * Class - FormFieldsConnection
 */
class FormFieldsConnection extends AbstractConnection {
	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		// @todo register to rootQuery.
	}

	/**
	 * {@inheritDoc}
	 */
	public static function register(): void {
		// @todo register to rootQuery.
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 */
	public static function get_connection_args(): array {
		return [
			'ids'         => [
				'type'        => [ 'list_of' => 'ID' ],
				'description' => __( 'Array of form field IDs to return.', 'wp-graphql-gravity-forms' ),
			],
			'adminLabels' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Array of form field adminLabels to return.', 'wp-graphql-gravity-forms' ),
			],
			'fieldTypes'  => [
				'type'        => [ 'list_of' => FormFieldTypeEnum::$type ],
				'description' => __( 'Array of Gravity Forms Field types to return.', 'wp-graphql-gravity-forms' ),
			],
			'pageNumber'  => [
				'type'        => 'Int',
				'description' => __( 'The form page number to return.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
