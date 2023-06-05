<?php
/**
 * Enum Type - EntryStatusEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - EntryStatusEnum
 */
class EntryStatusEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryStatusEnum';

	// Individual elements.
	public const ACTIVE = 'active';
	public const SPAM   = 'spam';
	public const TRASH  = 'trash';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Status of entries to get. Default is ACTIVE.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'ACTIVE' => [
				'description' => __( 'Active entries (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ACTIVE,
			],
			'SPAM'   => [
				'description' => __( 'Spam entries.', 'wp-graphql-gravity-forms' ),
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
