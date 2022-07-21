<?php
/**
 * GraphQL Interface for a FormField with the `multiple_files_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithMultipleFiles
 */
class FieldWithMultipleFiles extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithMultipleFiles';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'multiple_files_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'canAcceptMultipleFiles' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether multiple files may be uploaded.', 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->multipleFiles ),
			],
			'maxFiles'               => [
				'type'        => 'Int',
				'description' => __( 'When the field is set to allow multiple files to be uploaded, this property is available to set a limit on how many may be uploaded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
