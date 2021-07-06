<?php
/**
 * GraphQL Object Type - PostImageFieldValue
 * Values for an individual Post title field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Interfaces\FieldValue;
use WPGraphQLGravityForms\Types\AbstractObject;
use WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty\PostImageFieldValueProperty;

/**
 * Class - PostImageFieldValue
 */
class PostImageFieldValue extends AbstractObject implements FieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'PostImageFieldValue';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Post title field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 */
	public function get_type_fields() : array {
		return [
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

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field value.
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		return PostImageFieldValueProperty::get( $entry, $field );
	}
}
