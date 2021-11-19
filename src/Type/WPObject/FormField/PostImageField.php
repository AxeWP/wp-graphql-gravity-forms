<?php
/**
 * GraphQL Object Type - PostImageField
 *
 * @see https://docs.gravityforms.com/post-image/
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField
 * @since   0.0.1
 * @since   0.2.0 Add missing properties.
 */

namespace WPGraphQL\GF\Type\WPObject\FormField;

use WPGraphQL\GF\Type\WPObject\FormField\FieldProperty;
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
	public static string $type = 'PostImageField';

	/**
	 * Type registered in Gravity Forms.
	 *
	 * @var string
	 */
	public static $gf_type = 'post_image';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'Gravity Forms Post Image field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return array_merge(
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
		);
	}
}
