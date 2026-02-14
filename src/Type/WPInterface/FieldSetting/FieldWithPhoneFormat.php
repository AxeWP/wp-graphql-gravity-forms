<?php
/**
 * GraphQL Interface for a FormField with the `phone_format_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;
use WPGraphQL\GF\Model\FormField;
use WPGraphQL\GF\Type\Enum\PhoneFieldFormatEnum;
use WPGraphQL\GF\Type\WPObject\PhoneFormat;

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
			'phoneFormatType'          => [
				'type'        => PhoneFieldFormatEnum::$type,
				'description' => static fn () => __( 'Determines the allowed format for phones. If the phone value does not conform with the specified format, the field will fail validation.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( FormField $field ) => $field->gfField->phoneFormat,
			],
			'phoneFormat'              => [
				'type'              => PhoneFieldFormatEnum::$type,
				'deprecationReason' => static fn () => __( 'Use `phoneFormatType` instead. The GraphQL type for this field will change in the next breaking release.', 'wp-graphql-gravity-forms' ),
				'description'       => static fn () => __( 'Determines the allowed format for phones. If the phone value does not conform with the specified format, the field will fail validation.', 'wp-graphql-gravity-forms' ),
			],
			'_phoneFormatExperimental' => [
				'type'              => PhoneFormat::$type,
				'description'       => static fn () => __( 'The phone format properties. Experimental', 'wp-graphql-gravity-forms' ),
				'deprecationReason' => static fn () => __( 'The `phoneFormat` field has been renamed to `phoneFormatType`. The `_phoneFormatExperimental` field will be replaced in a future release.', 'wp-graphql-gravity-forms' ),
				'resolve'           => static function ( $field ) {
					if ( empty( $field->phoneFormat ) ) {
						return null;
					}

					$gf_field = $field->gfField ?? null;

					// Ensure the field has the get_phone_format() method.
					if ( ! $gf_field instanceof GF_Field || ! method_exists( $gf_field, 'get_phone_format' ) ) {
						return null;
					}

					$format = $gf_field->get_phone_format();

					if ( ! is_array( $format ) ) {
						return null;
					}

					// Normalize values: convert false to null for nullable GraphQL fields.
					return [
						'label'       => isset( $format['label'] ) && false !== $format['label'] ? (string) $format['label'] : null,
						'mask'        => isset( $format['mask'] ) && false !== $format['mask'] ? (string) $format['mask'] : null,
						'regex'       => isset( $format['regex'] ) && false !== $format['regex'] ? (string) $format['regex'] : null,
						'instruction' => isset( $format['instruction'] ) && false !== $format['instruction'] ? (string) $format['instruction'] : null,
						'type'        => $field->phoneFormat,
					];
				},
			],
		];
	}
}
