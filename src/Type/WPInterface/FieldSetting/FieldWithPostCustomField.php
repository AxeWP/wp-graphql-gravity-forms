<?php
/**
 * GraphQL Interface for a FormField with the `post_custom_field_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithPostCustomField
 */
class FieldWithPostCustomField extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPostCustomFieldSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'post_custom_field_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'postMetaFieldName' => [
				'type'        => 'String',
				'description' => __( 'The post meta key to which the value should be assigned.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source) => ! empty( $source->postCustomFieldName ) ? $source->postCustomFieldName : null,
			],
		];
	}
}
