<?php
/**
 * GraphQL Object Type - MultiSelectFieldValue
 * Values for an individual MultiSelect field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use WPGraphQLGravityForms\Types\Field\MultiSelectField;

/**
 * Class - MultiSelectFieldValue
 */
class MultiSelectFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'MultiSelectFieldValue';

	/**
	 * Sets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'Multiselect field values.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @return array
	 */
	public function get_properties() : array {
		return [
			'values' => [
				'type'        => [ 'list_of' => 'String' ],
				'description' => __( 'Field values.', 'wp-graphql-gravity-forms' ),
			],
		];
	}

	/**
	 * Get the field values.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field values.
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		$entry_values = $entry[ $field['id'] ] ?? null;

		if ( empty( $entry_values ) ) {
			return [ 'values' => null ];
		}

		if ( is_string( $entry_values ) ) {
			$entry_values = json_decode( $entry_values );
		}

		return [
			'values' => $entry_values,
		];
	}
}
