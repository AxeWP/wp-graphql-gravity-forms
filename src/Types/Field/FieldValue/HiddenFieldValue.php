<?php
/**
 * GraphQL Object Type - HiddenFieldValue
 * Values for an individual Hidden field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.3.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;

/**
 * Class - HiddenFieldValue
 */
class HiddenFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'HiddenFieldValue';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Hidden field value.', 'wp-graphql-gravity-forms' );
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
