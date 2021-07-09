<?php
/**
 * GraphQL Input Type - PostImageInput
 * Input fields for a single Post Image.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

/**
 * Class - PostImageInput
 */
class PostImageInput extends AbstractInput {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ImageInput';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Input fields for a single post Image.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
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
