<?php
/**
 * GraphQL Interface for a FormField with the `add_icon_url` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithAddIconUrl
 */
class FieldWithAddIconUrl extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithAddIconUrlSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'add_icon_url_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'addIconUrl' => [
				'type'        => 'String',
				'description' => __( 'The URL of the image to be used for the add row button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
