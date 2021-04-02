<?php
/**
 * GraphQL Object Type - ConsentFieldValue
 * Values for an individual Consent field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.3.0
 * @since   0.3.1 `value` changed to type `String`.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Types\Field\ConsentField;

/**
 * Class - ConsentFieldValue
 */
class ConsentFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ConsentFieldValue';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Consent field value.', 'wp-graphql-gravity-forms' );
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
				'description' => __( 'The value. Returns the consent message on `true`, `null` on false.', 'wp-graphql-gravity-forms' ),
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
				'value' => $entry[ $field['inputs'][1]['id'] ] ?? null,
			];
	}
}
