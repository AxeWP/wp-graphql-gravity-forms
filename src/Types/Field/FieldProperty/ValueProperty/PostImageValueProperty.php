<?php
/**
 * GraphQL Object Type - PostImageValuePropery
 * An individual property for the 'value' PostImage field property.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use WPGraphQLGravityForms\Types\AbstractObject;

/**
 * Class - PostImageValueProperty
 */
class PostImageValueProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PostImageValueProperty';

	/**
	 * Sets the field type description.
	 *
	 * @return string
	 */
	public function get_type_description(): string {
		return __( 'The individual properties for each element of the PostImage value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields(): array {
		return [
			'altText'     => [
				'type'        => 'String',
				'description' => __( 'The image alt text.', 'wp-graphql-gravity-forms' ),
			],
			'caption'     => [
				'type'        => 'String',
				'description' => __( 'The image caption.', 'wp-graphql-gravity-forms' ),
			],
			'description' => [
				'type'        => 'String',
				'description' => __( 'The image description.', 'wp-graphql-gravity-forms' ),
			],
			'title'       => [
				'type'        => 'String',
				'description' => __( 'The image title.', 'wp-graphql-gravity-forms' ),
			],
			'url'         => [
				'type'        => 'String',
				'description' => __( 'The image url.', 'wp-graphql-gravity-forms' ),
			],
		];
	}
}
