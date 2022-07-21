<?php
/**
 * GraphQL Interface for a FormField with the `border_width_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FormFieldSetting;

use WPGraphQL\GF\Extensions\GFSignature\Type\Enum\SignatureFieldBorderWidthEnum;
use WPGraphQL\GF\Type\WPInterface\FormFieldSetting\AbstractFormFieldSetting;

/**
 * Class - FieldWithBorderWidth
 */
class FieldWithBorderWidth extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBorderWidth';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'border_width_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'borderWidth' => [
				'type'        => SignatureFieldBorderWidthEnum::$type,
				'description' => __( 'Width of the border around the signature area.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
