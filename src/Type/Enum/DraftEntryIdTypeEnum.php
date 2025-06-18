<?php
/**
 * Enum Type - DraftEntryIdTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - DraftEntryIdTypeEnum
 */
class DraftEntryIdTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'DraftEntryIdTypeEnum';

	// Individual elements.
	public const ID           = 'global_id';
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
				'description' => static fn () => __( 'Unique global ID for the object.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ID,
			],
			'RESUME_TOKEN' => [
				'description' => static fn () => __( 'The resume token assigned by Gravity Forms. Used by draft entries.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RESUME_TOKEN,
			],
		];
	}
}
