<?php
/**
 * GraphQL Interface for a FormField with the `file_extensions_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithFileExtensions
 */
class FieldWithFileExtensions extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithFileExtensionsSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'file_extensions_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'allowedExtensions' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'A comma-delimited list of the file extensions which may be uploaded.', 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->allowedExtensions ) ? explode( ',', $source->allowedExtensions ) : null,
			],
		];
	}
}
