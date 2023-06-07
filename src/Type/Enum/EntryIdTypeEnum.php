<?php
/**
 * Enum Type - EntryIdTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - EntryIdTypeEnum
 */
class EntryIdTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryIdTypeEnum';

	// Individual elements.
	public const ID           = 'global_id';
	public const DATABASE_ID  = 'database_id';
	public const RESUME_TOKEN = 'resume_token';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The Type of Identifier used to fetch a single resource.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'ID'           => [
				'description' => __( 'Unique global ID for the object.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ID,
			],
			'DATABASE_ID'  => [
				'description' => __( 'The database ID assigned by Gravity Forms. Used by submitted entries.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DATABASE_ID,
			],
			'RESUME_TOKEN' => [
				'description' => __( 'The resume token assigned by Gravity Forms. Used by draft entries.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RESUME_TOKEN,
			],
		];
	}
}
