<?php
/**
 * GraphQL Interface for a FormField with the `phone_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\Enum\PhoneFieldFormatEnum;

/**
 * Class - FieldWithPhoneFormat
 */
class FieldWithPhoneFormat extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPhoneFormat';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'phone_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'phoneFormat' => [
				'type'        => PhoneFieldFormatEnum::$type,
				'description' => __( 'Determines the allowed format for phones. If the phone value does not conform with the specified format, the field will fail validation.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
