<?php
/**
 * GraphQL Interface for a FormField with the `admin_label_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithAdminLabel
 */
class FieldWithAdminLabel extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithAdminLabel';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'admin_label_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'adminLabel' => [
				'type'        => 'String',
				'description' => __( 'When specified, the value of this property will be used on the admin pages instead of the label. It is useful for fields with long labels.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
