<?php
/**
 * GraphQL Interface for a FormField with the `box_width_setting` setting.
 *
 * @package  WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Extensions\GFSignature\Type\WPInterface\FieldSetting;

use WPGraphQL\GF\Type\WPInterface\FieldSetting\AbstractFieldSetting;

/**
 * Class - FieldWithBoxWidth
 */
class FieldWithBoxWidth extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithBoxWidthSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'box_width_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'boxWidth' => [
				'type'        => 'Int',
				'description' => __( 'Width of the signature field in pixels.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
