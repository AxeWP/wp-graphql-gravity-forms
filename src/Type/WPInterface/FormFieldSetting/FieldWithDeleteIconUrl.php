<?php
/**
 * GraphQL Interface for a FormField with the `delete_icon_url_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithDeleteIconUrl
 */
class FieldWithDeleteIconUrl extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithDeleteIconUrl';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'delete_icon_url_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'deleteIconUrl' => [
				'type'        => 'String',
				'description' => __( 'The URL of the image to be used for the delete row button.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
