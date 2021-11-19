<?php
/**
 * GraphQL Edge Type - EntryUser
 * Creates a 1:1 relationship between an Entry and the User who created it.
 *
 * @package WPGraphQL\GF\Type\WPObject\Entry
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\WPObject\Entry;

use WP_User;
use WPGraphQL\Model\User;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Interfaces\Field;
use WPGraphQL\GF\Type\WPObject\AbstractObject;

use WPGraphQL\GF\Type\WPObject\Entry\Entry;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Creates a 1:1 relationship between an Entry and the User who created it.
 */
class EntryUser extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryUser';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $field_name = 'createdBy';

	/**
	 * {@inheritDoc}
	 */
	public static function register( TypeRegistry $type_registry = null ) : void {
		register_graphql_object_type(
			static::$type,
			static::prepare_config(
				[
					'description'     => static::get_description(),
					'fields'          => static::get_fields(),
					'eagerlyLoadType' => static::$should_load_eagerly,
				]
			)
		);

		self::register_field();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The user who created the entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'node' => [
				'type'        => 'User',
				'description' => __( 'The user who created the entry.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Register EntryUser query.
	 */
	public static function register_field() : void {
		register_graphql_field(
			Entry::$type,
			self::$field_name,
			[
				'type'        => self::$type,
				'description' => __( 'The user who created the entry.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $entry ) : array {
					$user = isset( $entry['createdById'] ) ? get_userdata( $entry['createdById'] ) : null;

					if ( ! $user instanceof WP_User ) {
						throw new UserError( __( 'The user who created this entry could not be found.', 'wp-graphql-gravity-forms' ) );
					}

					return [
						'node' => new User( $user ),
					];
				},
			]
		);
	}
}
