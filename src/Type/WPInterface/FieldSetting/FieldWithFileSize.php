<?php
/**
 * GraphQL Interface for a FormField with the `file_size_setting` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

declare( strict_types = 1 );

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithFileSize
 */
class FieldWithFileSize extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithFileSizeSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'file_size_setting';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'maxFileSize' => [
				'type'        => 'Int',
				'description' => __( 'The maximum size (in MB) an uploaded file may be .', 'wp-graphql-gravity-forms' ),
				'resolve'     => static function ( $source ) {
					// Fall back to the WP max upload size if the field setting is not set, to mimic GF frontend behavior.
					return ! empty( $source->maxFileSize ) ? (int) $source->maxFileSize : ( wp_max_upload_size() / 1048576 );
				},
			],
		];
	}
}
