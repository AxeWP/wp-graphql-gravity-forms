<?php
/**
 * Enum Type - FormStatusEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.0.1
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormStatusEnum
 */
class FormStatusEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormStatusEnum';

	// Individual elements.
	public const ACTIVE           = 'ACTIVE';
	public const INACTIVE         = 'INACTIVE';
	public const TRASHED          = 'TRASHED';
	public const INACTIVE_TRASHED = 'INACTIVE_TRASHED';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Status of forms to get. Default is ACTIVE.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			self::ACTIVE           => [
				'description' => __( 'Active forms (default).', 'wp-graphql-gravity-forms' ),
				'value'       => self::ACTIVE,
			],
			self::INACTIVE         => [
				'description' => __( 'Inactive forms.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INACTIVE,
			],
			self::TRASHED          => [
				'description' => __( 'Active forms in the trash.', 'wp-graphql-gravity-forms' ),
				'value'       => self::TRASHED,
			],
			self::INACTIVE_TRASHED => [
				'description' => __( 'Inactive forms in the trash.', 'wp-graphql-gravity-forms' ),
				'value'       => self::INACTIVE_TRASHED,
			],
		];
	}
}
