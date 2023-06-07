<?php
/**
 * Enum Type - EntryTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since 0.10.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - EntryTypeEnum
 */
class EntryTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'EntryTypeEnum';

	// Individual elements.
	public const DRAFT     = 'draft';
	public const PARTIAL   = 'partial';
	public const SUBMITTED = 'submitted';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The type of Gravity Forms entry.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'DRAFT'     => [
				'description' => __( 'A Gravity Forms draft entry.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DRAFT,
			],
			'PARTIAL'   => [
				'description' => __( 'A Gravity Forms partial entry.', 'wp-graphql-gravity-forms' ),
				'value'       => self::PARTIAL,
			],
			'SUBMITTED' => [
				'description' => __( 'A submitted Gravity Forms entry.', 'wp-graphql-gravity-forms' ),
				'value'       => self::SUBMITTED,
			],
		];
	}
}
