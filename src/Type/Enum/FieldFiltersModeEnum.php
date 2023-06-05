<?php
/**
 * Enum Type - FieldFiltersModeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FieldFiltersModeEnum
 */
class FieldFiltersModeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FieldFiltersModeEnum';

	// Individual elements.
	public const ALL = 'all';
	public const ANY = 'any';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Whether to filter by ALL or ANY of the field filters. Default is ALL.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'ALL' => [
				'description' => __( 'All field filters (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ALL,
			],
			'ANY' => [
				'description' => __( 'Any field filters.', 'wp-graphql-gravity-forms' ),
				'value'       => self::ANY,
			],
		];
	}
}
