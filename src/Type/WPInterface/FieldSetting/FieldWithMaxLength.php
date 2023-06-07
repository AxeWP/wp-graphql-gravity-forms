<?php
/**
 * GraphQL Interface for a FormField with the `maxlen_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

use GF_Field;

/**
 * Class - FieldWithMaxLength
 */
class FieldWithMaxLength extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithMaxLengthSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'maxlen_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'maxLength' => [
				'type'        => 'Int',
				'description' => __( 'Specifies the maximum number of characters allowed in a text or textarea (paragraph) field.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( GF_Field $field ): int {
					return (int) $field['maxLength'];
				},
			],
		];
	}
}
