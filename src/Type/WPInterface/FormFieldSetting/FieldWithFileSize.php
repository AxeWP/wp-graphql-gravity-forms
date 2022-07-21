<?php
/**
 * GraphQL Interface for a FormField with the `file_size_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithFileSize
 */
class FieldWithFileSize extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithFileSize';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'file_size_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'maxFileSize' => [
				'type'        => 'Int',
				'description' => __( 'The maximum size (in MB) an uploaded file may be .', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
