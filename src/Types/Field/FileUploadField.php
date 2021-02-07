<?php
/**
 * GraphQL Object Type - FileUploadField
 *
 * @see https://docs.gravityforms.com/gf_field_fileupload/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;

/**
 * Class - FileUploadField
 */
class FileUploadField extends Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'FileUploadField';

	/**
	 * Type registered in Gravity Forms.
	 */
	const GF_TYPE = 'fileupload';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
	}

	/**
	 * Register Object type to GraphQL schema.
	 */
	public function register_type() {
		register_graphql_object_type(
			self::TYPE,
			[
				'description' => __( 'Gravity Forms File Upload field.', 'wp-graphql-gravity-forms' ),
				'fields'      => array_merge(
					$this->get_global_properties(),
					$this->get_custom_properties(),
					FieldProperty\DescriptionProperty::get(),
					FieldProperty\ErrorMessageProperty::get(),
					FieldProperty\InputNameProperty::get(),
					FieldProperty\IsRequiredProperty::get(),
					FieldProperty\SizeProperty::get(),
					[
						'allowedExtensions' => [
							'type'        => 'String',
							'description' => __( 'A comma-delimited list of the file extensions which may be uploaded.', 'wp-graphql-gravity-forms' ),
						],
						'maxFiles'          => [
							'type'        => 'String',
							'description' => __( 'When the field is set to allow multiple files to be uploaded, this property is available to set a limit on how many may be uploaded.', 'wp-graphql-gravity-forms' ),
						],
						'maxFileSize'       => [
							'type'        => 'Integer',
							'description' => __( 'The maximum size (in MB) an uploaded file may be .', 'wp-graphql-gravity-forms' ),
						],
						'multipleFiles'     => [
							'type'        => 'Boolean',
							'description' => __( 'Indicates whether multiple files may be uploaded.', 'wp-graphql-gravity-forms' ),
						],
					],
				),
			]
		);
	}
}
