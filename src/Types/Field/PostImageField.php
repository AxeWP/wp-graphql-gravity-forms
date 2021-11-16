<?php
/**
 * GraphQL Object Type - PostImageField
 *
 * @see https://docs.gravityforms.com/post-image/
 *
 * @package WPGraphQL\GF\Types\Field
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Types\Field;

use WPGraphQL\GF\Types\Field\FieldProperty;
use WPGraphQL\GF\Utils\Utils;

/**
 * Class - PostImageField
 */
class PostImageField extends AbstractFormField {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PostImageField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'post_image';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Gravity Forms Post Image field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return array_merge(
			$this->get_global_properties(),
			$this->get_custom_properties(),
			FieldProperty\AdminLabelProperty::get(),
			FieldProperty\AllowedExtensionsProperty::get(),
			FieldProperty\ErrorMessageProperty::get(),
			FieldProperty\IsRequiredProperty::get(),
			FieldProperty\DescriptionPlacementProperty::get(),
			FieldProperty\DescriptionProperty::get(),
			FieldProperty\LabelProperty::get(),
			FieldProperty\SubLabelPlacementProperty::get(),
			FieldProperty\VisibilityProperty::get(),
			[
				'displayAlt'         => [
					'type'        => 'Boolean',
					'description' => __( 'Controls the visibility of the alt metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				],
				'displayCaption'     => [
					'type'        => 'Boolean',
					'description' => __( 'Controls the visibility of the caption metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				],
				'displayDescription' => [
					'type'        => 'Boolean',
					'description' => __( 'Controls the visibility of the description metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				],
				'displayTitle'       => [
					'type'        => 'Boolean',
					'description' => __( 'Controls the visibility of the title metadata for Post Image fields.', 'wp-graphql-gravity-forms' ),
				],
				'postFeaturedImage'  => [
					'type'        => 'Boolean',
					'description' => __( "Whether the image field should be used to set the post's Featured Image", 'wp-graphql-gravity-forms' ),
				],
			],
			/**
			* Deprecated field properties.
			*
			* @since 0.7.0
			*/
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AdminOnlyProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\AllowsPrepopulateProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\InputNameProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
			// translators: Gravity Forms Field type.
			Utils::deprecate_property( FieldProperty\SizeProperty::get(), sprintf( __( 'This property is not associated with the Gravity Forms %s type.', 'wp-graphql-gravity-forms' ), self::$type ) ),
		);
	}
}
