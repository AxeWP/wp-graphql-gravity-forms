<?php
/**
 * GraphQL Interface for a FormField with the `number_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\NumberFieldFormatEnum;

/**
 * Class - FieldWithNumberFormat
 */
class FieldWithNumberFormat extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithNumberFormatSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'number_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'numberFormat' => [
				'type'        => NumberFieldFormatEnum::$type,
				'description' => __( 'Specifies the format allowed for the number field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
