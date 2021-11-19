<?php
/**
 * GraphQL Object Type - PostImageValuePropery
 * An individual property for the 'value' PostImage field property.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

use WPGraphQL\GF\Type\WPObject\AbstractObject;


/**
 * Class - PostImageValueProperty
 */
class PostImageValueProperty extends AbstractObject {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static string $type = 'PostImageValueProperty';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'The individual properties for each element of the PostImage value field.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_fields(): array {
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
