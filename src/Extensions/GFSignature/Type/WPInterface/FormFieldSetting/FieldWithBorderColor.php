<?php
/**
 * GraphQL Interface for a FormField with the `border_color_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Type\WPInterface\FormFieldSetting\AbstractFormFieldSetting;

/**
 * Class - FieldWithBorderColor
 */
class FieldWithBorderColor extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBorderColor';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'border_color_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'borderColor' => [
				'type'        => 'String',
				'description' => __( 'Color to be used for the border around the signature area. Can be any valid CSS color value.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
