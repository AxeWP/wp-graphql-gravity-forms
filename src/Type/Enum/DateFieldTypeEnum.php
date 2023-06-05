<?php
/**
 * Enum Type - DateFieldTypeEnum
 *
 * @package WPGraphQL\GF\Type\Enum,
 * @since   0.4.0
 */

namespace WPGraphQL\GF\Type\Enum;

/**
 * Class - DateFieldTypeEnum
 */
class DateFieldTypeEnum extends AbstractEnum {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'DateFieldTypeEnum';

	// Individual elements.
	public const FIELD    = 'datefield';
	public const DROPDOWN = 'datedropdown';
	public const PICKER   = 'datepicker';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Type of date field to display.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_values(): array {
		return [
			'FIELD'    => [
				'description' => __( 'A simple date field.', 'wp-graphql-gravity-forms' ),
				'value'       => self::FIELD,
			],
			'DROPDOWN' => [
				'description' => __( 'A date dropdown.', 'wp-graphql-gravity-forms' ),
				'value'       => self::DROPDOWN,
			],
			'PICKER'   => [
				'description' => __( 'A datepicker.', 'wp-graphql-gravity-forms' ),
				'value'       => self::PICKER,
			],
		];
	}
}
