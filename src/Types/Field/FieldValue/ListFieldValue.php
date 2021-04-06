<?php
/**
 * GraphQL Object Type - ListFieldValue
 * Values for an individual List field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldValue
 * @since   0.0.1
 * @since   0.3.0 Return early if value is null or empty.
 * @since   0.3.0 Fix array structure and deprecate `value` in favor of `values`.
 */

namespace WPGraphQLGravityForms\Types\Field\FieldValue;

use GF_Field;
use GF_Field_List;
use GraphQL\Error\UserError;

/**
 * Class - ListFieldValue
 */
class ListFieldValue extends AbstractFieldValue {
	/**
	 * Type registered in WPGraphQL.
	 *
	 * @var string
	 */
	public static $type = 'ListFieldValue';

	/**
	 * Sets the field type description.
	 *
	 * @since 0.4.0
	 */
	public function get_type_description() : string {
		return __( 'List field values.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the properties for the Field.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	public function get_properties() : array {
		return [
			'listValues' => [
				'type'        => [ 'list_of' => ListInputValue::$type ],
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
	 *
	 * @throws UserError .
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		if ( ! $field instanceof GF_Field_List ) {
			throw new UserError( __( 'Error! Trying to use a non ListField as a ListField!', 'wp-graphql-gravity-forms' ) );
		}

		$entry_values = $entry[ $field['id'] ] ?? null;

		if ( empty( $entry_values ) ) {
			return [];
		}

		if ( is_string( $entry_values ) ) {
			$entry_values = maybe_unserialize( $entry_values );
		} else {
			$entry_values = $field->create_list_array_recursive( $entry_values );
		}

		// If columns are enabled, save each row-value pair.
		if ( $field['enableColumns'] ) {

			// Save each row-value pair.
			$listValues = array_map(
				function( $row ) {
					$row_values = [];

					foreach ( $row as $single_value ) {
						$row_values[] = $single_value;
					}

					return [
						'value'  => $row_values, // Deprecated @since 0.3.0.
						'values' => $row_values,
					];
				},
				$entry_values
			);
			return compact( 'listValues' );
		}

		// If no columns, entry values can be mapped directly to 'value'.
		$listValues = array_map(
			function( $single_value ) {
				return [
					'values' => [ $single_value ], // $single_value must be Iteratable.
					'value'  => [ $single_value ], // Deprecated @since 0.3.0.
				];
			},
			$entry_values
		);

		return compact( 'listValues' );
	}
}
