<?php
/**
 * GraphQL Interface for a FormField with the `post_image_featured_image` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FormFieldSetting
 * @since  @todo
 */

namespace WPGraphQL\GF\Type\WPInterface\FormFieldSetting;

/**
 * Class - FieldWithPostImageFeaturedImage
 */
class FieldWithPostImageFeaturedImage extends AbstractFormFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPostImageFeaturedImage';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'post_image_featured_image';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields() : array {
		return [
			'isFeaturedImage' => [
				'type'        => 'Boolean',
				'description' => __( "Whether the image field should be used to set the post's Featured Image", 'wp-graphql-gravity-forms' ),
				'resolve'     => fn( $source ) => ! empty( $source->postFeaturedImage ),
			],
		];
	}
}
