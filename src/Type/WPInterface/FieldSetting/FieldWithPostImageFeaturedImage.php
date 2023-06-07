<?php
/**
 * GraphQL Interface for a FormField with the `post_image_featured_image` setting.
 *
 * @package WPGraphQL\GF\Type\Interface\FieldSetting
 * @since 0.12.0
 */

namespace WPGraphQL\GF\Type\WPInterface\FieldSetting;

/**
 * Class - FieldWithPostImageFeaturedImage
 */
class FieldWithPostImageFeaturedImage extends AbstractFieldSetting {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'GfFieldWithPostImageFeaturedImageSetting';

	/**
	 * The name of GF Field Setting
	 *
	 * @var string
	 */
	public static string $field_setting = 'post_image_featured_image';

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'isFeaturedImage' => [
				'type'        => 'Boolean',
				'description' => __( "Whether the image field should be used to set the post's Featured Image", 'wp-graphql-gravity-forms' ),
				'resolve'     => static fn ( $source ) => ! empty( $source->postFeaturedImage ),
			],
		];
	}
}
