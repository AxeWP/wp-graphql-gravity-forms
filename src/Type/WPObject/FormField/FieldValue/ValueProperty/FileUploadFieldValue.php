<?php
/**
 * GraphQL Object Type - FileUploadFieldValue
 * An individual property for the 'fileUpload' field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty
 * @since   0.11.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;

/**
 * Class - FileUploadFieldValue
 */
class FileUploadFieldValue extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'FileUploadFieldValue';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'The individual file properties from an uploaded file.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'basePath' => [
				'type'        => 'String',
				'description' => __( 'The path to the parent directory of the file.', 'wp-graphql-gravity-forms' ),
			],
			'baseUrl'  => [
				'type'        => 'String',
				'description' => __( 'The base url to the parent directory of the file.', 'wp-graphql-gravity-forms' ),
			],
			'filename' => [
				'type'        => 'String',
				'description' => __( 'The filename.', 'wp-graphql-gravity-forms' ),
			],
			'url'      => [
				'type'        => 'String',
				'description' => __( 'The url to the file.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
