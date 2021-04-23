<?php
/**
 * GraphQL Field - ListFieldValueProperty
 * Values for an individual List field.
 *
 * @package WPGraphQLGravityForms\Types\Field\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQLGravityForms\Types\Field\FieldProperty\ValueProperty;

use GF_Field;
use GF_Field_List;
use GraphQL\Error\UserError;

/**
 * Class - ListFieldValueProperty
 */
class ListFieldValueProperty extends AbstractValueProperty {
	/**
	 * Type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $type = 'ListField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static $field_name = 'listValues';

	/**
	 * Gets the field type description.
	 */
	public function get_type_description() : string {
		return __( 'List field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * Gets the GraphQL type for the field.
	 *
	 * @return array
	 */
	public function get_field_type() : array {
		return [ 'list_of' => ListValueProperty::$type ];
	}

	/**
	 * Get the field value.
	 *
	 * @param array    $entry Gravity Forms entry.
	 * @param GF_Field $field Gravity Forms field.
	 *
	 * @return array Entry field value.
	 *
	 * @throws UserError .
	 */
	public static function get( array $entry, GF_Field $field ) : array {
		if ( ! $field instanceof GF_Field_List ) {
			throw new UserError( __( 'Error! Trying to use a non ListField as a ListField!', 'wp-graphql-gravity-forms' ) );
		}

		$entry_values = $entry[ $field->id ] ?? null;

		if ( empty( $entry_values ) ) {
			return [];
		}

		if ( is_string( $entry_values ) ) {
			$entry_values = maybe_unserialize( $entry_values );
		} else {
			$entry_values = $field->create_list_array_recursive( $entry_values );
		}

		// If columns are enabled, save each row-value pair.
		if ( $field->enableColumns ) {

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
			return $listValues;
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

		return $listValues;
	}
}
