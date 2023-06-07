<?php
/**
 * GraphQL Interface for a FormField with the `border_style_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Extensions\GFSignature\Type\Enum\SignatureFieldBorderStyleEnum;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithBorderStyle
 */
class FieldWithBorderStyle extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBorderStyleSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'border_style_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'borderStyle' => [
				'type'        => SignatureFieldBorderStyleEnum::$type,
				'description' => __( 'Border style to be used around the signature area.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
