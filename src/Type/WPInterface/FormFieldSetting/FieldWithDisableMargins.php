<?php
/**
 * GraphQL Interface for a FormField with the `disable_margins_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithDisableMargins
 */
class FieldWithDisableMargins extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDisableMargins';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'disable_margins_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'hasMargins' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether the default margins are turned on to align the HTML content with other fields.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => empty( $source->disableMargins ),
			],
		];
	}
}
