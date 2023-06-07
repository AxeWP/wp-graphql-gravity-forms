<?php
/**
 * GraphQL Input Type - PostImageFieldInput
 * Input fields for a single Post Image.
 *
 * @package WPGraphQL\GF\Type\Input
 * @since   0.7.0
 */

namespace WPGraphQL\GF\Type\Input;

/**
 * Class - PostImageFieldInput
 */
class PostImageFieldInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'ImageInput';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description(): string {
		return __( 'Input fields for a single post Image.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
		return [
			'image'       => [
				'type'        => [ 'non_null' => 'Upload' ],
				'description' => __( 'The image to be uploaded.', 'wp-graphql-gravity-forms' ),
			],
			'altText'     => [
				'type'        => 'String',
				'description' => __( 'The image alt text.', 'wp-graphql-gravity-forms' ),
			],
			'title'       => [
				'type'        => 'String',
				'description' => __( 'The image title.', 'wp-graphql-gravity-forms' ),
			],
			'caption'     => [
				'type'        => 'String',
				'description' => __( 'The image caption.', 'wp-graphql-gravity-forms' ),
			],
			'description' => [
				'type'        => 'String',
				'description' => __( 'The image description.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
