<?php
/**
 * GraphQL Interface for a FormField with the `multiple_files_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithMultipleFiles
 */
class FieldWithMultipleFiles extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithMultipleFilesSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'multiple_files_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'canAcceptMultipleFiles' => [
				'type'        => 'Boolean',
				'description' => __( 'Indicates whether multiple files may be uploaded.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->multipleFiles ),
			],
			'maxFiles'               => [
				'type'        => 'Int',
				'description' => __( 'When the field is set to allow multiple files to be uploaded, this property is available to set a limit on how many may be uploaded.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
