<?php
/**
 * Enum Type - FormIdTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormIdTypeEnum
 */
class FormIdTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormIdTypeEnum';

	// Individual elements.
	public const ID          = 'global_id';
	public const DATABASE_ID = 'database_id';

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
			'ID'          => [
				'description' => __( 'Unique global ID for the object.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ID,
			],
			'DATABASE_ID' => [
				'description' => __( 'The database ID assigned by Gravity Forms.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DATABASE_ID,
			],
		];
	}
}
