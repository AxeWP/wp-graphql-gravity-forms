<?php
/**
 * GraphQL Object Type - FileUploadField
 *
 * @see https://docs.gravityforms.com/gf_field_fileupload/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - FileUploadField
 */
class FileUploadField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FileUploadField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'fileupload';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms File Upload field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
			FieldProperty\AllowedExtensionsProperty::get(),
			[
				'maxFiles'      => [
					'type'        => 'String',
					'description' => __( 'When the field is set to allow multiple files to be uploaded, this property is available to set a limit on how many may be uploaded.', 'wp-graphql-gravity-forms' ),
				],
				'maxFileSize'   => [
					'type'        => 'Int',
					'description' => __( 'The maximum size (in MB) an uploaded file may be .', 'wp-graphql-gravity-forms' ),
				],
				'multipleFiles' => [
					'type'        => 'Boolean',
					'description' => __( 'Indicates whether multiple files may be uploaded.', 'wp-graphql-gravity-forms' ),
				],
			],
			static::get_fields_from_gf_settings(),
		);
	}
}
