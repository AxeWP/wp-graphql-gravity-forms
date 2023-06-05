<?php
/**
 * GraphQL Interface for a FormField with the `disable_margins_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithDisableMargins
 */
class FieldWithDisableMargins extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDisableMarginsSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'disable_margins_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'hasMargins' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the default margins are turned on to align the HTML content with other fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => empty( $source->disableMargins ),
			],
		];
	}
}
