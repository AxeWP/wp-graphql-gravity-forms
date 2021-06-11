<?php
/**
 * GraphQL Edge Type - EntryUser
 * Creates a 1:1 relationship between an Entry and the User who created it.
 *
 * @package WPGraphQLGravityForms\Types\Entry
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Entry;

use WP_User;
use WPGraphQL\Model\User;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Entry\Entry;

/**
 * Creates a 1:1 relationship between an Entry and the User who created it.
 */
class EntryUser extends AbstractObject implements Field {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EntryUser';

	/**
	 * Field registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $field_name = 'createdBy';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		parent::register_hooks();
		add_action( 'graphql_register_types', [ $this, 'register_field' ] );
	}

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description() : string {
		return __( 'The user who created the entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_type_fields() : array {
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
	public function register_field() : void {
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
