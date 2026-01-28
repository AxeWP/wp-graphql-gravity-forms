<?php
/**
 * GraphQL Interface for a FormField with the `phone_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\Enum\PhoneFieldFormatEnum;
use WPGraphQL\GF\Type\WPObject\PhoneFormatProperties;

/**
 * Class - FieldWithPhoneFormat
 */
class FieldWithPhoneFormat extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPhoneFormatSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'phone_format_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'phoneFormat'           => [
				'type'        => PhoneFieldFormatEnum::$type,
				'description' => static fn () => __( 'Determines the allowed format for phones. If the phone value does not conform with the specified format, the field will fail validation.', 'wp-graphql-gravity-forms' ),
			],
			'phoneFormatProperties' => [
				'type'        => PhoneFormatProperties::$type,
				'description' => static fn () => __( 'The properties of the selected phone format, including label, mask, regex, instruction and type.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $field ) {
					if ( empty( $field->phoneFormat ) ) {
						return null;
					}

					// Get all available phone formats, including custom ones from gform_phone_formats filter.
					$phone_formats = apply_filters(
						'gform_phone_formats', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
						[
							'standard'      => [
								'label'       => '(###) ###-####',
								'mask'        => '(999) 999-9999',
								'regex'       => '/^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/',
								'instruction' => '(###) ###-####',
								'type'        => 'standard',
							],
							'international' => [
								'label'       => 'International',
								'mask'        => false,
								'regex'       => false,
								'instruction' => __( 'International phone numbers must start with a + followed by the country code and phone number.', 'wp-graphql-gravity-forms' ),
								'type'        => 'international',
							],
						]
					);

					// Return the properties for the selected format.
					return $phone_formats[ $field->phoneFormat ] ?? null;
				},
			],
		];
	}
}
