<?php
/**
 * Enum Type - EntryStatusEnum
 *
 * @package WPGraphQLGravityForms\Types\Enum,
 * @since   0.4.0
 */

namespace WPGraphQLGravityForms\Types\Enum;

/**
 * Class - EntryStatusEnum
 */
class EntryStatusEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'EntryStatusEnum';

	// Individual elements.
	const ACTIVE = 'active';
	const SPAM   = 'spam';
	const TRASH  = 'trash';

	/**
	 * Sets the Enum type description.
	 *
	 * @return string Enum type description.
	 */
	public function get_type_description() : string {
		return __( 'Status of entries to get. Default is ACTIVE.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Sets the Enum type values.
	 *
	 * @return array
	 */
	public function set_values() : array {
		return [
			'ACTIVE' => [
				'description' => __( 'Active entries (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ACTIVE,
			],
			'SPAM'   => [
				'description' => __( 'Spam entries', 'wp-graphql-gravity-forms' ),
				'value'       => self::SPAM,
			],
			'TRASH'  => [
				'description' => __( 'Entries in the trash.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TRASH,
			],
			'ALL'    => [
				'description' => __( 'All entries.', 'wp-graphql-gravity-forms' ),
				'value'       => null,
			],
		];
	}
}
