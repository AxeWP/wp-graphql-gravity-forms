<?php
/**
 * GraphQL Field - ListFieldValue
 * Values for an individual List field.
 *
 * @package WPGraphQL\GF\Type\WPObject\FormField\FieldProperties\ValueProperty
 * @since   0.5.0
 */

namespace WPGraphQL\GF\Type\WPObject\FormField\FieldValue;

use GF_Field;
use GF_Field_List;
use GraphQL\Error\UserError;
use WPGraphQL\GF\Type\WPObject\FormField\FieldValue\ValueProperty\ListValueProperty;

/**
 * Class - ListFieldValue
 */
class ListFieldValue extends AbstractFieldValue {
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
	 * {@inheritDoc}
	 *
	 * @throws UserError .
	 */
	public static function get( array $entry_values, GF_Field $field ) : array {
		if ( ! $field instanceof GF_Field_List ) {
			throw new UserError( __( 'Error! Trying to use a non ListField as a ListField!', 'wp-graphql-gravity-forms' ) );
		}

		$field_values = $entry_values[ $field->id ] ?: null;

		if ( empty( $field_values ) ) {
			return [];
		}

		if ( is_string( $field_values ) ) {
			$field_values = maybe_unserialize( $field_values );
		} else {
			$field_values = $field->create_list_array_recursive( $field_values );
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
				$field_values
			);
		}

		// If no columns, entry values can be mapped directly to 'value'.
		return array_map(
			function( $single_value ) {
				return [
					'values' => [ $single_value ], // $single_value must be Iteratable.
				];
			},
			$field_values
		);
	}
}
