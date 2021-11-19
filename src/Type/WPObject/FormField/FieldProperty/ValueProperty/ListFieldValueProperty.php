<?php
/**
 * GraphQL Field - ListFieldValueProperty
 * Values for an individual List field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldProperty\ValueProperty;

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
	public static string $type = 'ListField';

	/**
	 * Existing type registered in WPGraphQL that we are adding the field to.
	 *
	 * @var string
	 */
	public static string $field_name = 'listValues';

	/**
	 * {@inheritDoc}
	 */
	public static function get_description() : string {
		return __( 'List field value.', 'wp-graphql-gravity-forms' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_field_type() : array {
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

		$entry_values = $entry[ $field->id ] ?: null;

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
			return array_map(
				function( $row ) {
					$row_values = [];

					foreach ( $row as $single_value ) {
						$row_values[] = $single_value;
					}

					return [
						'values' => $row_values,
					];
				},
				$entry_values
			);
		}

		// If no columns, entry values can be mapped directly to 'value'.
		return array_map(
			function( $single_value ) {
				return [
					'values' => [ $single_value ], // $single_value must be Iteratable.
				];
			},
			$entry_values
		);
	}
}
