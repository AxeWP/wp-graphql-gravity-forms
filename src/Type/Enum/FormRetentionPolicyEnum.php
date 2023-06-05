<?php
/**
 * Enum Type - FormRetentionPolicyEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.10.1
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormRetentionPolicyEnum
 */
class FormRetentionPolicyEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormRetentionPolicyEnum';

	// Individual elements.
	public const DELETE = 'delete';
	public const RETAIN = 'retain';
	public const TRASH  = 'trash';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The Personal Data retention policy.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'DELETE' => [
				'description' => __( 'Entries will be deleted automatically after a specified number of days.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DELETE,
			],
			'RETAIN' => [
				'description' => __( 'Entries will be retain indefinitely.', 'wp-graphql-gravity-forms' ),
				'value'       => self::RETAIN,
			],
			'TRASH'  => [
				'description' => __( 'Entries will be trashed automatically after a specified number of days.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TRASH,
			],
		];
	}
}
