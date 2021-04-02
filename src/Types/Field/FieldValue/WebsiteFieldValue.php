<?php
/**
 * GraphQL Object Type - WebsiteFieldValue
 * Values for an individual Website field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Types\Field\WebsiteField;

/**
 * Class - WebsiteFieldValue
 */
class WebsiteFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'WebsiteFieldValue';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Website field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_properties() : array {
		return [
			'value' => [
				'type'        => 'String',
				'description' => __( 'The value.', 'wp-graphql-gravity-forms' ),
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
			return [
				'value' => isset( $entry[ $field['id'] ] ) ? (string) $entry[ $field['id'] ] : null,
			];
	}
}
