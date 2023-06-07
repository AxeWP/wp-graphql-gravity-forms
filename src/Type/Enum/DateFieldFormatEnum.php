<?php
/**
 * Enum Type - DateFieldFormatEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - DateFieldFormatEnum
 */
class DateFieldFormatEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'DateFieldFormatEnum';

	// Individual elements.
	public const MDY       = 'mdy';
	public const DMY       = 'dmy';
	public const DMY_DASH  = 'dmy_dash';
	public const DMY_DOT   = 'dmy_dot';
	public const YMD_SLASH = 'ymd_slash';
	public const YMD_DASH  = 'ymd_dash';
	public const YMD_DOT   = 'ymd_dot';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'How the DateField date is displayed.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'MDY'       => [
				'description' => __( 'mm/dd/yyyy format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::MDY,
			],
			'DMY'       => [
				'description' => __( 'dd/mm/yyyy format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DMY,
			],
			'DMY_DASH'  => [
				'description' => __( 'dd-mm-yyyy format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DMY_DASH,
			],
			'DMY_DOT'   => [
				'description' => __( 'dd.mm.yyyy format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DMY_DOT,
			],
			'YMD_SLASH' => [
				'description' => __( 'yyyy/mm/dd format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::YMD_SLASH,
			],
			'YMD_DASH'  => [
				'description' => __( 'yyyy/mm/dd format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::YMD_DASH,
			],
			'YMD_DOT'   => [
				'description' => __( 'yyyy.mm.dd format.', 'wp-graphql-gravity-forms' ),
				'value'       => self::YMD_DOT,
			],
		];
	}
}
