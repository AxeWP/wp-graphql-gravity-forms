<?php
/**
 * GraphQL Interface for a FormField with the `content_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithContent
 */
class FieldWithContent extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithContent';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'content_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'content' => [
				'type'        => 'String',
				'description' => __( 'Content of an HTML block field to be displayed on the form.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
