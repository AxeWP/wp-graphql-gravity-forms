<?php
/**
 * GraphQL Interface for a FormField with the `border_color_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithBorderColor
 */
class FieldWithBorderColor extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBorderColorSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'border_color_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'borderColor' => [
				'type'        => 'String',
				'description' => __( 'Color to be used for the border around the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
