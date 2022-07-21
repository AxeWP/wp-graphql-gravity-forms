<?php
/**
 * GraphQL Interface for a FormField with the `number_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\Enum\NumberFieldFormatEnum;

/**
 * Class - FieldWithNumberFormat
 */
class FieldWithNumberFormat extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithNumberFormat';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'number_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'numberFormat' => [
				'type'        => NumberFieldFormatEnum::$type,
				'description' => __( 'Specifies the format allowed for the number field.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
