<?php
/**
 * GraphQL Interface for a FormField with the `delete_icon_url_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithDeleteIconUrl
 */
class FieldWithDeleteIconUrl extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDeleteIconUrlSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'delete_icon_url_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'deleteIconUrl' => [
				'type'        => 'String',
				'description' => __( 'The URL of the image to be used for the delete row button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
