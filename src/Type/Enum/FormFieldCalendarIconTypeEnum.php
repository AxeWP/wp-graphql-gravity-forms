<?php
/**
 * Enum Type - FormFieldCalendarIconTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - FormFieldCalendarIconTypeEnum
 */
class FormFieldCalendarIconTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FormFieldCalendarIconTypeEnum';

	// Individual elements.
	public const CALENDAR = 'calendar';
	public const CUSTOM   = 'custom';
	public const NONE     = 'none';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'How the date field displays its calendar icon.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'CALENDAR' => [
				'description' => __( 'Default calendar icon.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CALENDAR,
			],
			'CUSTOM'   => [
				'description' => __( 'Custom calendar icon.', 'wp-graphql-gravity-forms' ),
				'value'       => self::CUSTOM,
			],
			'NONE'     => [
				'description' => __( 'No calendar icon.', 'wp-graphql-gravity-forms' ),
				'value'       => self::NONE,
			],
		];
	}
}
