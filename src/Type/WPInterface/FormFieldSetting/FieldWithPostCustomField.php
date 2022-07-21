<?php
/**
 * GraphQL Interface for a FormField with the `post_custom_field_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithPostCustomField
 */
class FieldWithPostCustomField extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPostCustomField';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'post_custom_field_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'postMetaFieldName' => [
				'type'        => 'String',
				'description' => __( 'The post meta key to which the value should be assigned.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source) => ! empty( $source->postCustomFieldName ) ? $source->postCustomFieldName : null,
			],
		];
	}
}
