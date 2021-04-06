<?php
/**
 * GraphQL Object Type - FileUploadField
 *
 * @see https://docs.gravityforms.com/gf_field_fileupload/
 *
 * @package WPGraphQLGravityForms\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties, and deprecate unused ones.
 */

namespace WPGraphQLGravityForms\Types\Field;

use WPGraphQLGravityForms\Types\Field\FieldProperty;
use WPGraphQLGravityForms\Utils\Utils;

/**
 * Class - FileUploadField
 */
class FileUploadField extends AbstractField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'FileUploadField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'fileupload';

	/**
	 * Sets the field type description.
	 */
	protected function get_type_description() : string {
		return __( 'Gravity Forms File Upload field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	protected function get_properties() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AdminOnlyProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\SizeProperty::get(),
			FieldProperty\VisibilityProperty::get(),
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
			/**
			* Deprecated field properties.
			*
			* @since 0.2.0
			*/

				// translators: Gravity Forms Field type.
				Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
				Utils::deprecate_property( FieldProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
