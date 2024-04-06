<?php
/**
 * Abstract GraphQL Connection
 *
 * @package WPGraphQL\GF\Connection;
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Connection;

use WPGraphQL\GF\Interfaces\Hookable;
use WPGraphQL\GF\Interfaces\Registrable;

/**
 * Class - AbstractConnection
 */
abstract class AbstractConnection implements Hookable, Registrable {
	/**
	 * GraphQL field name in node tree.
	 *
	 * @var string
	 */
	public static $from_field_name;

	/**
	 * {@inheritDoc}
	 */
	public static function register_hooks(): void {
		add_action( 'graphql_register_types', [ static::class, 'register' ] );
	}

	/**
	 * Gets custom connection configuration arguments, such as the resolver, edgeFields, connectionArgs, etc.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public static function get_connection_args(): array {
		return [];
	}

	/**
	 * Returns a filtered array of connection args.
	 *
	 * @param string[] $filter_by .
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public static function get_filtered_connection_args( ?array $filter_by = null ): array {
		$connection_args = static::get_connection_args();

		if ( empty( $filter_by ) ) {
			return $connection_args;
		}

		$filtered_args = [];
		foreach ( $filter_by as $filter ) {
			$filtered_args[ $filter ] = $connection_args[ $filter ];
		}

		return $filtered_args;
	}
}
