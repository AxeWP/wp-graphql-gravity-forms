<?php
/**
 * GraphQL Interface for a FormField with the `add_icon_url` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithAddIconUrl
 */
class FieldWithAddIconUrl extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithAddIconUrl';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'add_icon_url_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'addIconUrl' => [
				'type'        => 'String',
				'description' => __( 'The URL of the image to be used for the add row button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
