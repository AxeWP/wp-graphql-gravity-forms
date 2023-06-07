<?php
/**
 * GraphQL Interface for a FormField with the `border_width_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Extensions\GFSignature\Type\Enum\SignatureFieldBorderWidthEnum;
use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithBorderWidth
 */
class FieldWithBorderWidth extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBorderWidthSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'border_width_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'borderWidth' => [
				'type'        => SignatureFieldBorderWidthEnum::$type,
				'description' => __( 'Width of the border around the signature area.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
